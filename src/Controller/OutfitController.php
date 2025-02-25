<?php

namespace App\Controller;

use App\Entity\Outfit;
use App\Form\OutfitType;
use App\Repository\OutfitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/outfit')]
final class OutfitController extends AbstractController
{
    #[Route('/', name: 'app_outfit_index', methods: ['GET'])]
    public function index(OutfitRepository $outfitRepository): Response
    {
        return $this->render('outfit/index.html.twig', [
            'outfits' => $outfitRepository->findBy(['customer' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_outfit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $outfit = new Outfit();
        $customer = $this->getUser();

        if (!$customer instanceof \App\Entity\User) {
            throw new \LogicException('The logged in user is not a valid customer.');
        }

        $outfit->setCustomer($customer);

        $form = $this->createForm(OutfitType::class, $outfit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($outfit);
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/new.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outfit_show', methods: ['GET'])]
    public function show(Outfit $outfit): Response
    {
        if ($outfit->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('outfit/show.html.twig', [
            'outfit' => $outfit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_outfit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outfit $outfit, EntityManagerInterface $entityManager): Response
    {
        if ($outfit->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(OutfitType::class, $outfit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/edit.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outfit_delete', methods: ['POST'])]
    public function delete(Request $request, Outfit $outfit, EntityManagerInterface $entityManager): Response
    {
        if ($outfit->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$outfit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($outfit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
    }
}
