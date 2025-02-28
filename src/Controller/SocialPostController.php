<?php

namespace App\Controller;

use App\Entity\SocialPost;
use App\Form\SocialPostType;
use App\Repository\SocialPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/social/post')]
final class SocialPostController extends AbstractController
{
    #[Route(name: 'app_social_post_index', methods: ['GET'])]
    public function index(SocialPostRepository $socialPostRepository): Response
    {
        return $this->render('social_post/index.html.twig', [
            'social_posts' => $socialPostRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_social_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $socialPost = new SocialPost();
        $form = $this->createForm(SocialPostType::class, $socialPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($socialPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_social_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('social_post/new.html.twig', [
            'social_post' => $socialPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_social_post_show', methods: ['GET'])]
    public function show(SocialPost $socialPost): Response
    {
        return $this->render('social_post/show.html.twig', [
            'social_post' => $socialPost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_social_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocialPost $socialPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocialPostType::class, $socialPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_social_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('social_post/edit.html.twig', [
            'social_post' => $socialPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_social_post_delete', methods: ['POST'])]
    public function delete(Request $request, SocialPost $socialPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$socialPost->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($socialPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_social_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
