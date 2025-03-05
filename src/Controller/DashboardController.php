<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\SocialPostRepository;
use App\Repository\WardrobeItemRepository;
use App\Repository\OutfitRepository;
use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'app_dashboard_')]
class DashboardController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SocialPostRepository $socialPostRepository,
        private WardrobeItemRepository $wardrobeItemRepository,
        private OutfitRepository $outfitRepository,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $now = new DateTimeImmutable();
        $today = new DateTimeImmutable('today');

        $userCount = $this->userRepository->count([]);
        $outfitCount = $this->outfitRepository->count([]);
        $categoryCount = $this->categoryRepository->count([]);
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
            'categoryCount'          => $categoryCount,
            'combinedCount'          => $combinedCount,
            'averageOutfitsPerDay'   => $averageOutfitsPerDay,
            'averageCombinedPerDay'  => $averageCombinedPerDay,
            'outfitsToday'           => $outfitsToday,
            'combinedToday'          => $combinedToday,
        ]);
    }

    #[Route('/user', name: 'user')]
    public function dashboardUser(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('dashboard/userDashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'user_new')]
    public function newUser( Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('app_dashboard_user');
        }

        return $this->render('dashboard/user_new.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/outfit', name: 'outfit', methods: ['GET'])]
    public function dashboardOutfit(): Response
    {
        $outfits = $this->outfitRepository->findAll();

        return $this->render('dashboard/outfitDashboard.html.twig', [
            'outfits' => $outfits,
        ]);
    }

    #[Route('/category', name: 'category')]
    public function dashboardCategory(CategoryRepository $categoryRepository): Response
    {
        // Récupère toutes les catégories
        $categories = $categoryRepository->findAll();

        return $this->render('dashboard/categoryDashboard.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'category_new')]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie créée avec succès.');
            return $this->redirectToRoute('app_dashboard_category');
        }

        return $this->render('dashboard/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/delete', name: 'category_delete', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_dashboard_category');
    }
}