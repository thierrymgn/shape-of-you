<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_my_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function myProfile(FollowRepository $followRepository): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser(); // Symfony garantit que c'est bien un User

        $followers = $followRepository->findBy(['following' => $currentUser]);
        $following = $followRepository->findBy(['follower' => $currentUser]);
        $WardrobeItems = $currentUser->getWardrobeItems();
        $Outfits = $currentUser->getOutfits();

        return $this->render('user/my_profile.html.twig', [
            'userProfile' => $currentUser,
            'followers' => $followers,
            'following' => $following,
            'wardrobeItems' => $WardrobeItems,
            'outfits' => $Outfits,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_profile')]
    public function userProfile(User $user, FollowRepository $followRepository): Response
    {
        /** @var ?User $currentUser */
        $currentUser = $this->getUser();

        $isFollowing = $currentUser ?
            $followRepository->findOneBy([
                'follower' => $currentUser,
                'following' => $user,
            ])
            : null;

        $followers = $followRepository->findBy(['following' => $user]);
        $following = $followRepository->findBy(['follower' => $user]);

        return $this->render('user/user_profile.html.twig', [
            'userProfile' => $user,
            'isFollowing' => $isFollowing,
            'followers' => $followers,
            'following' => $following,
        ]);
    }
}
