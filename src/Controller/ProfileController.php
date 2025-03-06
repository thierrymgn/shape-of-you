<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté');
        }

        if (!$user instanceof User) {
            throw new \LogicException('The logged in user is not a valid customer.');
        }

        $profile = $user->getProfile();

        if (!$profile) {
            $profile = new Profile();
            $profile->setCustomer($user);
        } else {
            $this->denyAccessUnlessGranted('edit', $profile);
        }

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès!');

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté');
        }

        if (!$user instanceof User) {
            throw new \LogicException('The logged in user is not a valid customer.');
        }

        $profile = $user->getProfile();

        $this->denyAccessUnlessGranted('view', $profile);

        if (!$profile) {
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
            'user' => $user,
        ]);
    }
}
