<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/follow')]
class FollowController extends AbstractController
{
    #[Route('/', name: 'app_follow_index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();

        $follows = $em->getRepository(Follow::class)->findBy(['follower' => $currentUser]);

        return $this->render('follow/index.html.twig', [
            'follows' => $follows,
        ]);
    }

    #[Route('/{id}', name: 'app_follow', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function follow(User $userToFollow, EntityManagerInterface $em): RedirectResponse
    {
        $currentUser = $this->getUser();

        if ($currentUser === $userToFollow) {
            $this->addFlash('error', 'You cannot follow yourself.');

            return $this->redirectToRoute('app_user_profile', ['id' => $userToFollow->getId()]);
        }

        $followRepository = $em->getRepository(Follow::class);

        $existingFollow = $followRepository->findOneBy([
            'follower' => $currentUser,
            'following' => $userToFollow,
        ]);

        if ($existingFollow) {
            $this->addFlash('error', 'You are already following this user.');

            return $this->redirectToRoute('app_user_profile', ['id' => $userToFollow->getId()]);
        }

        $follow = new Follow();
        /* @phpstan-ignore-next-line */
        $follow->setFollower($currentUser);
        $follow->setFollowing($userToFollow);
        $follow->setCreatedAt(new \DateTimeImmutable());

        $em->persist($follow);
        $em->flush();

        $this->addFlash('success', 'You are now following '.$userToFollow->getFirstName());

        return $this->redirectToRoute('app_user_profile', ['id' => $userToFollow->getId()]);
    }

    #[Route('/unfollow/{id}', name: 'app_unfollow', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unfollow(User $userToUnfollow, EntityManagerInterface $em): RedirectResponse
    {
        $currentUser = $this->getUser();

        $followRepository = $em->getRepository(Follow::class);

        $follow = $followRepository->findOneBy([
            'follower' => $currentUser,
            'following' => $userToUnfollow,
        ]);

        if (!$follow) {
            $this->addFlash('error', 'You are not following this user.');

            return $this->redirectToRoute('app_user_profile', ['id' => $userToUnfollow->getId()]);
        }

        $em->remove($follow);
        $em->flush();

        $this->addFlash('success', 'You have unfollowed '.$userToUnfollow->getFirstName());

        return $this->redirectToRoute('app_user_profile', ['id' => $userToUnfollow->getId()]);
    }
}
