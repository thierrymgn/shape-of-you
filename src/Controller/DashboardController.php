<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Form\CategoryType;
use App\Form\RegistrationFormType;
use App\Form\WardrobeItemAdminType;
use App\Repository\CategoryRepository;
use App\Repository\OutfitRepository;
use App\Repository\UserRepository;
use App\Repository\WardrobeItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'app_dashboard_')]
class DashboardController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private WardrobeItemRepository $wardrobeItemRepository,
        private OutfitRepository $outfitRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $now = new \DateTimeImmutable();
        $today = new \DateTimeImmutable('today');

        $userCount = $this->userRepository->count([]);
        $outfitCount = $this->outfitRepository->count([]);
        $categoryCount = $this->categoryRepository->count([]);
        $wardrobeCount = $this->wardrobeItemRepository->count([]);
        $combinedCount = $outfitCount + $wardrobeCount;

        $firstOutfit = $this->outfitRepository->createQueryBuilder('o')
            ->select('o.createdAt')
            ->orderBy('o.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $daysOutfits = $firstOutfit ? $firstOutfit['createdAt']->diff($now)->days : 0;
        $averageOutfitsPerDay = ($daysOutfits > 0) ? round($outfitCount / $daysOutfits, 2) : $outfitCount;

        $firstWardrobe = $this->wardrobeItemRepository->createQueryBuilder('o')
            ->select('o.createdAt')
            ->orderBy('o.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $daysWardrobe = $firstWardrobe ? $firstWardrobe['createdAt']->diff($now)->days : 0;
        $averageWardrobePerDay = ($daysWardrobe > 0) ? round($wardrobeCount / $daysWardrobe, 2) : $wardrobeCount;

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

        $wardrobeToday = $this->wardrobeItemRepository->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.createdAt >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();

        $combinedToday = $outfitsToday + $wardrobeToday;

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'userCount' => $userCount,
            'outfitCount' => $outfitCount,
            'categoryCount' => $categoryCount,
            'combinedCount' => $combinedCount,
            'wardrobeCount' => $wardrobeCount,
            'averageWardrobePerDay' => $averageWardrobePerDay,
            'averageOutfitsPerDay' => $averageOutfitsPerDay,
            'averageCombinedPerDay' => $averageCombinedPerDay,
            'outfitsToday' => $outfitsToday,
            'combinedToday' => $combinedToday,
        ]);
    }

    #[Route('/user', name: 'user')]
    public function dashboardUser(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }
        $users = $this->userRepository->findAll();

        return $this->render('dashboard/userDashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'user_new')]
    public function newUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

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

    #[Route('/user/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_dashboard_user');
    }

    #[Route('/outfit', name: 'outfit', methods: ['GET'])]
    public function dashboardOutfit(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $outfits = $this->outfitRepository->findAll();

        return $this->render('dashboard/outfitDashboard.html.twig', [
            'outfits' => $outfits,
        ]);
    }

    #[Route('/category', name: 'category')]
    public function dashboardCategory(CategoryRepository $categoryRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        // Récupère toutes les catégories
        $categories = $categoryRepository->findAll();

        return $this->render('dashboard/categoryDashboard.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'category_new')]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

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
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_dashboard_category');
    }

    #[Route('/wardrobe', name: 'wardrobe')]
    public function dashboardWardrobeItems(WardrobeItemRepository $wardrobeItemRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $wardrobeItems = $wardrobeItemRepository->findAll();

        return $this->render('dashboard/wardrobeDashboard.html.twig', [
            'wardrobe_items' => $wardrobeItems,
        ]);
    }

    #[Route('/wardrobe/new', name: 'wardrobe_new', methods: ['GET', 'POST'])]
    public function newWardrobeItem(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $wardrobeItem = new WardrobeItem();
        $form = $this->createForm(WardrobeItemAdminType::class, $wardrobeItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User|null $user */
            $user = $this->getUser();
            if (!$user) {
                throw new \LogicException('L\'utilisateur doit être authentifié.');
            }
            $wardrobeItem->setCustomer($user);
            $entityManager->persist($wardrobeItem);
            $entityManager->flush();

            $this->addFlash('success', 'Wardrobe item créé avec succès.');

            return $this->redirectToRoute('app_dashboard_wardrobe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/wardrobe_new.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wardrobe/{id}/edit', name: 'wardrobe_edit', methods: ['GET', 'POST'])]
    public function editWardrobeItem(Request $request, WardrobeItem $wardrobeItem, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(WardrobeItemAdminType::class, $wardrobeItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Wardrobe item mis à jour avec succès.');

            return $this->redirectToRoute('app_dashboard_wardrobe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/wardrobe_edit.html.twig', [
            'wardrobe_item' => $wardrobeItem,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wardrobe/{id}/delete', name: 'wardrobe_delete', methods: ['POST'])]
    public function deleteWardrobeItem(Request $request, WardrobeItem $wardrobeItem, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete'.$wardrobeItem->getId(), $request->request->get('_token'))) {
            $entityManager->remove($wardrobeItem);
            $entityManager->flush();

            $this->addFlash('success', 'Wardrobe item supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_dashboard_wardrobe', [], Response::HTTP_SEE_OTHER);
    }
}
