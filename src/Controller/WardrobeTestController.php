<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Form\WardrobeItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WardrobeTestController extends AbstractController
{
    #[Route('/wardrobe/test', name: 'app_wardrobe_test')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wardrobeItem = new WardrobeItem();
        $form = $this->createForm(WardrobeItemType::class, $wardrobeItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wardrobeItem->setCustomer($entityManager->getRepository(User::class)->findOneBy([]));
            $wardrobeItem->setCreatedAt(new \DateTimeImmutable());
            $wardrobeItem->setUpdatedAt(new \DateTimeImmutable());

            $category = $entityManager->getRepository(Category::class)->findOneBy([]);
            $wardrobeItem->setCategory($category);

            $entityManager->persist($wardrobeItem);
            $entityManager->flush();

            $this->addFlash('success', 'Item ajouté avec succès !');

            return $this->redirectToRoute('app_wardrobe_test');
        }

        return $this->render('wardrobe_test/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
