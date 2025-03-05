<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\SocialPostRepository;
use App\Repository\WardrobeItemRepository;
use App\Repository\OutfitRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private SocialPostRepository $socialPostRepository,
        private WardrobeItemRepository $wardrobeItemRepository,
        private OutfitRepository $outfitRepository
    ) {}

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $now = new DateTimeImmutable();
        $today = new DateTimeImmutable('today');

        $userCount = $this->userRepository->count([]);
        $outfitCount = $this->outfitRepository->count([]);
        $combinedCount = $outfitCount;

        $firstOutfit = $this->outfitRepository->createQueryBuilder('o')
            ->select('o.createdAt')
            ->orderBy('o.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $daysOutfits = $firstOutfit ? $firstOutfit['createdAt']->diff($now)->days : 0;
        $averageOutfitsPerDay = ($daysOutfits > 0) ? round($outfitCount / $daysOutfits, 2) : $outfitCount;

        $firstDates = [];

        if ($firstOutfit) {
            $firstDates[] = $firstOutfit['createdAt'];
        }

        if (count($firstDates) > 0) {
            $combinedFirstDate = min($firstDates);
            $daysCombined = $combinedFirstDate->diff($now)->days;
            $averageCombinedPerDay = ($daysCombined > 0) ? round($combinedCount / $daysCombined, 2) : $combinedCount;
        } else {
            $averageCombinedPerDay = 0;
        }

        $outfitsToday = $this->outfitRepository->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.createdAt >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();

        $combinedToday = $outfitsToday;


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'userCount'              => $userCount,
            'outfitCount'            => $outfitCount,
            'combinedCount'          => $combinedCount,
            'averageOutfitsPerDay'   => $averageOutfitsPerDay,
            'averageCombinedPerDay'  => $averageCombinedPerDay,
            'outfitsToday'           => $outfitsToday,
            'combinedToday'          => $combinedToday,
        ]);
    }

    #[Route('/dashboard/user', name: 'app_dashboard_user')]
    public function dashboardUser(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('dashboard/userDashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/dashboard/outfit', name: 'app_dashboard_outfit', methods: ['GET'])]
    public function dashboardOutfit(OutfitRepository $outfitRepository): Response
    {
        $outfits = $outfitRepository->findAll();

        return $this->render('dashboard/outfitDashboard.html.twig', [
            'outfits' => $outfits,
        ]);
    }
}
