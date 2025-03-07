<?php

namespace App\Controller;

use App\Entity\WardrobeItem;
use App\Form\WardrobeItemType;
use App\Repository\WardrobeItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/wardrobe/item')]
final class WardrobeItemController extends AbstractController
{
    #[Route(name: 'app_wardrobe_item_index', methods: ['GET'])]
    public function index(WardrobeItemRepository $wardrobeItemRepository): Response
    {
        return $this->render('wardrobe_item/index.html.twig', [
            'wardrobe_items' => $wardrobeItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_wardrobe_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wardrobeItem = new WardrobeItem();
        $form = $this->createForm(WardrobeItemType::class, $wardrobeItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // début image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('wardrobeItem_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
                $wardrobeItem->setImage($newFilename);
            }
            // fin image upload

            /** @var \App\Entity\User|null $user */
            $user = $this->getUser();
            if (!$user) {
                throw new \LogicException('L\'utilisateur doit être authentifié.');
            }
            $wardrobeItem->setCustomer($user);
            $entityManager->persist($wardrobeItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_wardrobe_item_index', [], Response::HTTP_SEE_OTHER);
        }

        $apiKey = $_ENV['OPENAI_API_KEY_WARDROBE'];

        return $this->render('wardrobe_item/new.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'form' => $form,
            'apiKey' => $apiKey,
        ]);
    }

    #[Route('/{id}', name: 'app_wardrobe_item_show', methods: ['GET'])]
    public function show(WardrobeItem $wardrobeItem, WardrobeItemRepository $wardrobeItemRepository): Response
    {
        $wardrobe_items = $wardrobeItemRepository->findBy(['customer' => $this->getUser()]);

        return $this->render('wardrobe_item/show.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'wardrobe_items' => $wardrobe_items,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wardrobe_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WardrobeItem $wardrobeItem, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $wardrobeItem);

        $form = $this->createForm(WardrobeItemType::class, $wardrobeItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_wardrobe_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wardrobe_item/edit.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_wardrobe_item_delete', methods: ['POST'])]
    public function delete(Request $request, WardrobeItem $wardrobeItem, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $wardrobeItem);
        if ($this->isCsrfTokenValid('delete'.$wardrobeItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wardrobeItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wardrobe_item_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/recommend', name: 'app_wardrobe_item_recommend', methods: ['POST'])]
    public function recommend(WardrobeItem $wardrobeItem, HttpClientInterface $httpClient): Response
    {
        // Détails du vêtement sélectionné
        $itemDetails = [
            'name' => $wardrobeItem->getName(),
            'brand' => $wardrobeItem->getBrand(),
            'color' => $wardrobeItem->getColor(),
            'category' => $wardrobeItem->getCategory()->getName(),
            'season' => $wardrobeItem->getSeason()->value,
        ];

        // Génération de la requête pour OpenAI
        $prompt = 'Je cherche des vêtements similaires à celui-ci : '.json_encode($itemDetails).
        ". Peux-tu me recommander 9 vêtements similaires ? Réponds uniquement sous forme de liste JSON avec :
        - 'name' (nom du vêtement)
        - 'brand' (marque)
        - 'color' (couleur)
        - 'category' (catégorie)
        
        Assure-toi que les liens d'images sont corrects et existants en ligne.";

        try {
            $response = $httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.$_ENV['OPENAI_API_KEY'],
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.7,
                ],
            ]);

            // Vérification du statut de la réponse
            $statusCode = $response->getStatusCode();
            $responseData = $response->getContent(false);

            if (200 !== $statusCode) {
                throw new \Exception('Erreur OpenAI: '.$statusCode.' - '.$responseData);
            }

            $responseData = $response->toArray();

            // Correction des guillemets pour éviter les erreurs de parsing JSON
            $recommendationsRaw = $responseData['choices'][0]['message']['content'] ?? '[]';
            $recommendationsJson = str_replace("'", '"', $recommendationsRaw);
            $recommendations = json_decode($recommendationsJson, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \Exception('Erreur de décodage JSON: '.json_last_error_msg());
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la récupération des recommandations : '.$e->getMessage());

            return $this->redirectToRoute('app_wardrobe_item_show', ['id' => $wardrobeItem->getId()]);
        }

        return $this->render('wardrobe_item/recommend.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'recommended_items' => $recommendations,
        ]);
    }

    #[Route('/ai/analyze', name: 'app_ai_analyze', methods: ['POST'])]
    public function analyze(Request $request): JsonResponse
    {
        // Récupérer le fichier image depuis le formulaire
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile) {
            return new JsonResponse(['error' => 'Aucun fichier image fourni.'], 400);
        }

        // Lire le contenu du fichier
        $imageContent = file_get_contents($uploadedFile->getPathname());
        $imageData = base64_encode($imageContent);
        $sampleImageData = substr($imageData, 0, 100).'...';

        // Construire le prompt pour l'API OpenAI
        $prompt = "Here is a base64 snippet of an image of a clothing item: $sampleImageData\n\n".
          "Please analyze this image and extract the following details:\n".
          "1. name: The name or style of the garment.\n".
          "2. brand: The brand or designer of the garment.\n".
          "3. color: The main color of the garment.\n".
          "4. description: A brief description of the garment’s style and features.\n\n".
          'Return your answer as a valid JSON object with the keys "name", "brand", "color", and "description". '.
          'If any detail cannot be determined, use an empty string for that key.';

        // Créer un client HTTP
        $client = HttpClient::create();

        // Récupérer la clé API depuis l'environnement
        $apiKey = trim($_ENV['OPENAI_API_KEY_WARDROBE']);

        if (!$apiKey) {
            return new JsonResponse(['error' => 'Clé API non configurée.'], 500);
        }

        try {
            // Appeler l'API OpenAI
            $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo-0125', // ou "gpt-4" si vous y avez accès
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert in clothing analysis. Extract garment details from image data.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                ],
            ]);

            $result = $response->toArray();
            $contentString = $result['choices'][0]['message']['content'] ?? '';
            // Nettoyer le contenu si nécessaire (par exemple, retirer les délimiteurs Markdown)
            $cleanContent = preg_replace('/```(json)?/', '', $contentString);
            $cleanContent = trim($cleanContent);

            $parsedOutput = json_decode($cleanContent, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                return new JsonResponse([
                    'error' => 'Erreur lors du parsing JSON: '.json_last_error_msg(),
                    'raw_output' => $cleanContent,
                ], 500);
            }

            return new JsonResponse($parsedOutput);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
