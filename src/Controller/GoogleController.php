<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class GoogleController extends AbstractController
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private TokenStorageInterface $tokenStorage;
    private RequestStack $requestStack;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    #[Route('/connect/google', name: 'connect_google')]
    public function connectGoogle(): Response
    {
        return $this->clientRegistry
            ->getClient('google')
            ->redirect([
                'profile', 'email', // Les scopes à demander
            ]);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectGoogleCheck(): Response
    {
        // Récupérer les informations de l'utilisateur Google
        $client = $this->clientRegistry->getClient('google');
        $googleUser = $client->fetchUser();

        // Vérifier si l'utilisateur existe déjà
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $googleUser->getEmail()]);

        if (!$user) {
            // Créer un nouvel utilisateur
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setGoogleId($googleUser->getId());
            $user->setFirstName($googleUser->getFirstName());
            $user->setLastName($googleUser->getLastName());

            // Générer un mot de passe aléatoire et le hacher
            $randomPassword = bin2hex(random_bytes(10)); // 20 caractères aléatoires
            $hashedPassword = $this->passwordHasher->hashPassword($user, $randomPassword);
            $user->setPassword($hashedPassword);

            // Sauvegarder l'utilisateur en base
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $this->loginUser($user);

        return $this->redirectToRoute('app_login');
    }

    private function loginUser(User $user): void
    {
        // Créer un token pour l'utilisateur
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        // Définir le token dans le TokenStorage
        $this->tokenStorage->setToken($token);

        // Accéder à la session via RequestStack
        $session = $this->requestStack->getSession();
        if ($session) {
            $session->set('_security_main', serialize($token));
        }
    }
}
