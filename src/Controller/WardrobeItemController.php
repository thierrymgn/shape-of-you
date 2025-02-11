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
    public function show(WardrobeItem $wardrobeItem): Response
    {
        return $this->render('wardrobe_item/show.html.twig', [
            'wardrobe_item' => $wardrobeItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wardrobe_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WardrobeItem $wardrobeItem, EntityManagerInterface $entityManager): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$wardrobeItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wardrobeItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wardrobe_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
