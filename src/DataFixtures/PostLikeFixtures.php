<?php

namespace App\DataFixtures;

use App\Entity\PostLike;
use App\Entity\SocialPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostLikeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur minimal pour la relation (userId)
        $user = new User();
        $user->setEmail('likeuser@example.com')
             ->setFirstName('Like')
             ->setLastName('User')
             ->setPassword('password'); // Pour le test, en clair (à hasher en production)
        $manager->persist($user);

        // Création d'un SocialPost minimal pour la relation (postId)
        $post = new SocialPost();
        $post->setTitle('Sample Social Post')
             ->setContent('This is a sample post content.')
             ->setImage('https://example.com/sample.jpg')
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());
        // Les champs likes_count et comments_count sont optionnels (nullable)
        $manager->persist($post);

        // Création d'un PostLike en renseignant tous les champs obligatoires
        $postLike = new PostLike();
        $postLike->setCreatedAt(new \DateTimeImmutable())
                 ->setUserId($user)   // Association avec l'utilisateur créé
                 ->setPostId($post);  // Association avec le SocialPost créé
        $manager->persist($postLike);

        $manager->flush();
    }
}
