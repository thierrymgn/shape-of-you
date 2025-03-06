<?php

namespace App\Controller;

use App\Entity\WardrobeItem;
use App\Form\WardrobeItemType;
use App\Repository\WardrobeItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

        return $this->render('wardrobe_item/new.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'form' => $form,
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
        $itemDetails = [
            'name' => $wardrobeItem->getName(),
            'brand' => $wardrobeItem->getBrand(),
            'color' => $wardrobeItem->getColor(),
            'category' => $wardrobeItem->getCategory()->getName(),
            'season' => $wardrobeItem->getSeason()->value
        ];
    
        $prompt = "Je cherche des vêtements similaires à celui-ci : " . json_encode($itemDetails) . 
                  ". Peux-tu me recommander 5 vêtements similaires ? Donne-moi uniquement une liste JSON avec des objets contenant 'name', 'brand', 'color' et 'category'.";
    
        try {
            $response = $httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['OPENAI_API_KEY'],
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.7,
                ],
            ]);
    
            $statusCode = $response->getStatusCode();
            $responseData = $response->getContent(false);
    
            if ($statusCode !== 200) {
                dd("Erreur OpenAI:", $statusCode, $responseData);
            }
    
            $responseData = $response->toArray();
            $recommendations = json_decode($responseData['choices'][0]['message']['content'], true);
    
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la récupération des recommandations : ' . $e->getMessage());
            return $this->redirectToRoute('app_wardrobe_item_show', ['id' => $wardrobeItem->getId()]);
        }
    
        return $this->render('wardrobe_item/recommend.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'recommended_items' => $recommendations
        ]);
    }
}
