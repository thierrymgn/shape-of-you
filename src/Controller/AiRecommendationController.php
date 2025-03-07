<?php

namespace App\Controller;

use App\Entity\AiAnalysis;
use App\Entity\OutfitItem;
use App\Entity\User;
use App\Enum\AnalysisType;
use App\Enum\WardrobeSeason;
use App\Service\OutfitRecommendationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ai')]
class AiRecommendationController extends AbstractController
{
    private OutfitRecommendationService $recommendationService;

    public function __construct(
        OutfitRecommendationService $recommendationService,
    ) {
        $this->recommendationService = $recommendationService;
    }

    #[Route('/recommend-outfits', name: 'app_ai_recommend_outfits', methods: ['GET', 'POST'])]
    public function recommendOutfits(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur connecté n\'est pas une instance valide de User.');
        }

        $occasion = null;
        $season = null;
        $outfits = [];
        $formSubmitted = false;

        if ($request->isMethod('POST')) {
            $occasion = $request->request->get('occasion');
            $seasonValue = $request->request->get('season');
            $season = WardrobeSeason::from($seasonValue);
            $formSubmitted = true;

            try {
                $outfits = $this->recommendationService->suggestOutfits(
                    $user,
                    $occasion,
                    $season,
                    3
                );

                if (empty($outfits)) {
                    $this->addFlash('warning', 'Nous n\'avons pas pu générer de suggestions...');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la génération des suggestions: '.$e->getMessage());
            }
        }

        return $this->render('ai/recommend_outfits.html.twig', [
            'occasions' => $this->getOccasions(),
            'seasons' => $this->getFormattedSeasons(),
            'outfits' => $outfits,
            'formSubmitted' => $formSubmitted,
            'occasion' => $occasion,
            'season' => $season,
        ]);
    }

    #[Route('/analyze-style', name: 'app_ai_analyze_style', methods: ['GET'])]
    public function analyzeStyle(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur connecté n\'est pas une instance valide de User.');
        }

        try {
            $styleAnalysis = $this->recommendationService->analyzeUserStyle($user);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'analyse de style: '.$e->getMessage());
            $styleAnalysis = [
                'status' => 'analysis_failed',
                'message' => 'L\'analyse a échoué. Veuillez réessayer plus tard.',
            ];
        }

        return $this->render('ai/style_analysis.html.twig', [
            'analysis' => $styleAnalysis,
        ]);
    }

    #[Route('/save-outfit/{id}', name: 'app_ai_save_outfit', methods: ['POST'])]
    public function saveOutfit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur connecté n\'est pas une instance valide de User.');
        }

        $sessionKey = 'suggested_outfits';
        $suggestedOutfits = $request->getSession()->get($sessionKey, []);

        $outfitToSave = null;
        foreach ($suggestedOutfits as $outfit) {
            if ($outfit->getId() === $id) {
                $outfitToSave = $outfit;
                break;
            }
        }

        if (!$outfitToSave) {
            $this->addFlash('error', 'Tenue non trouvée. Veuillez réessayer.');

            return $this->redirectToRoute('app_ai_recommend_outfits');
        }

        try {
            $permanentOutfit = clone $outfitToSave;
            //            $permanentOutfit->setId(null);
            $permanentOutfit->setCustomer($user);

            foreach ($outfitToSave->getOutfitItems() as $item) {
                $newItem = new OutfitItem();
                $newItem->setWardrobeItem($item->getWardrobeItem());
                $newItem->setPosition($item->getPosition());
                $newItem->setOutfit($permanentOutfit);
                $permanentOutfit->addOutfitItem($newItem);
            }

            $analysis = $entityManager->getRepository(AiAnalysis::class)->findOneBy([
                'analysisType' => AnalysisType::OUTFIT,
                'createdAt' => $outfitToSave->getCreatedAt(),
            ]);

            if ($analysis) {
                $analysis->setOutfitId($permanentOutfit);
            }

            $entityManager->persist($permanentOutfit);
            $entityManager->flush();

            $this->addFlash('success', 'La tenue a été enregistrée dans votre garde-robe.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de la tenue: '.$e->getMessage());
        }

        return $this->redirectToRoute('app_outfit_index');
    }

    /**
     * Retourne la liste des occasions disponibles.
     *
     * @return array<string, string>
     */
    private function getOccasions(): array
    {
        return [
            'Casual' => 'Casual',
            'Travail' => 'Travail',
            'Sport' => 'Sport',
            'Soirée' => 'Soirée',
            'Formel' => 'Formel',
            'Vacances' => 'Vacances',
            'Weekend' => 'Weekend',
            'Rendez-vous' => 'Rendez-vous',
        ];
    }

    /**
     * Retourne la liste des saisons formatées pour le formulaire.
     *
     * @return array<string, string>
     */
    private function getFormattedSeasons(): array
    {
        $formattedSeasons = [];
        foreach (WardrobeSeason::cases() as $season) {
            $formattedSeasons[ucfirst($season->value)] = $season->value;
        }

        return $formattedSeasons;
    }
}
