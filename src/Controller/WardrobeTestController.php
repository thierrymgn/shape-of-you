<?php

namespace App\Controller;

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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $wardrobeItem->setCustomer($this->getUser());
                $wardrobeItem->setCreatedAt(new \DateTimeImmutable());
                $wardrobeItem->setUpdatedAt(new \DateTimeImmutable());

                $entityManager->persist($wardrobeItem);
                $entityManager->flush();

                return $this->redirectToRoute('app_wardrobe_test');
            }

            return $this->render('wardrobe_test/index.html.twig', [
                'form' => $form->createView(),
            ], new Response(null, Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        return $this->render('wardrobe_test/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
