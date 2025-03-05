<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\SocialPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_comment_add', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function addComment(SocialPost $socialPost, Request $request, EntityManagerInterface $entityManager): Response
    {
        $content = trim($request->request->get('comment_content'));

        if (empty($content)) {
            $this->addFlash('error', 'Comment cannot be empty.');

            return $this->redirectToRoute('app_social_post_show', ['id' => $socialPost->getId()]);
        }

        $comment = new Comment();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $comment->setUserId($user);
        $comment->setPostId($socialPost);
        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_social_post_show', ['id' => $socialPost->getId()]);
    }

    #[Route('/delete/{id}', name: 'app_comment_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getUserId() !== $this->getUser()) {
            $this->addFlash('error', 'You can only delete your own comments.');

            return $this->redirectToRoute('app_social_post_show', ['id' => $comment->getPostId()->getId()]);
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_social_post_show', ['id' => $comment->getPostId()->getId()]);
    }
}
