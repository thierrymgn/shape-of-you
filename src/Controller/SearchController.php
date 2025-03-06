<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OutfitRepository;
use App\Repository\WardrobeItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, WardrobeItemRepository $wardrobeItemRepository, OutfitRepository $outfitRepository): Response
    {
        $query = $request->query->get('q');
        $results = [];

        if ($query) {
            $currentUser = $this->getUser();

            if (!$currentUser instanceof User) {
                throw new \LogicException('The logged in user is not a valid customer.');
            }

            $wardrobeItems = $wardrobeItemRepository->searchAllAccessible($query, $currentUser);

            $outfits = $outfitRepository->searchAllAccessible($query, $currentUser);

            $results = [
                'wardrobeItems' => $wardrobeItems,
                'outfits' => $outfits,
            ];
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
