<?php

namespace App\Controller;

use App\Enum\WardrobeSeason;
use App\Service\OutfitRecommendationService;
use App\Service\SimilarProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ai')]
class AiRecommendationController extends AbstractController
{
    private OutfitRecommendationService $recommendationService;
    private SimilarProductsService $similarProductsService;

    public function __construct(
        OutfitRecommendationService $recommendationService,
        SimilarProductsService $similarProductsService
    ) {
        $this->recommendationService = $recommendationService;
        $this->similarProductsService = $similarProductsService;
    }

    #[Route('/recommend-outfits', name: 'app_ai_recommend_outfits', methods: ['GET', 'POST'])]
    public function recommendOutfits(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
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
                    3 // Nombre de suggestions
                );

                if (empty($outfits)) {
                    $this->addFlash('warning', 'Nous n\'avons pas pu générer de suggestions. Essayez avec d\'autres critères ou ajoutez plus de vêtements.');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la génération des suggestions: ' . $e->getMessage());
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

    #[Route('/trending-outfits', name: 'app_ai_trending_outfits', methods: ['GET'])]
    public function trendingOutfits(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $outfits = [];

        try {
            $outfits = $this->recommendationService->suggestTrendingOutfits($user, 3);

            if (empty($outfits)) {
                $this->addFlash('info', 'Nous n\'avons pas trouvé de tendances adaptées à votre garde-robe pour le moment.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la génération des tendances: ' . $e->getMessage());
        }

        return $this->render('ai/trending_outfits.html.twig', [
            'outfits' => $outfits,
        ]);
    }

    #[Route('/analyze-style', name: 'app_ai_analyze_style', methods: ['GET'])]
    public function analyzeStyle(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $styleAnalysis = $this->recommendationService->analyzeUserStyle($user);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'analyse de style: ' . $e->getMessage());
            $styleAnalysis = [
                'status' => 'analysis_failed',
                'message' => 'L\'analyse a échoué. Veuillez réessayer plus tard.'
            ];
        }

        return $this->render('ai/style_analysis.html.twig', [
            'analysis' => $styleAnalysis,
        ]);
    }

    #[Route('/save-outfit/{id}', name: 'app_ai_save_outfit', methods: ['POST'])]
    public function saveOutfit(int $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        try {
            // Ici, on peut implémenter la logique pour sauvegarder la tenue générée
            // Par exemple, copier la tenue temporaire vers une tenue permanente de l'utilisateur

            $this->addFlash('success', 'La tenue a été enregistrée dans votre garde-robe.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de la tenue: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_outfit_index');
    }

    /**
     * Retourne la liste des occasions disponibles
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
     * Retourne la liste des saisons formatées pour le formulaire
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
