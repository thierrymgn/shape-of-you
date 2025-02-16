<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\CommentLike;
use App\Entity\SocialPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentLikeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur minimal pour la relation (userId)
        $user = new User();
        $user->setEmail('commentlikeuser@example.com')
             ->setFirstName('Comment')
             ->setLastName('Liker')
             ->setPassword('password'); // Pour le test, en clair (à hasher en production)
        $manager->persist($user);

        // Création d'un SocialPost minimal pour permettre de créer un commentaire
        $post = new SocialPost();
        $post->setTitle('Post for Comment')
             ->setContent('This is a sample social post used for comments.')
             ->setImage('https://example.com/post.jpg')
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($post);

        // Création d'un Comment minimal pour la relation (commentID)
        $comment = new Comment();
        $comment->setContent('This is a sample comment.')
                ->setLevel(1)
                ->setRepliesCount(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setUserId($user)    // L'auteur du commentaire
                ->setPostId($post);   // Le post auquel le commentaire est rattaché
        $manager->persist($comment);

        // Création d'un CommentLike en renseignant tous les champs obligatoires
        $commentLike = new CommentLike();
        $commentLike->setCreatedAt(new \DateTimeImmutable())
                    ->setUserId($user)     // Association avec l'utilisateur créé
                    ->setCommentID($comment); // Association avec le commentaire créé
        $manager->persist($commentLike);

        $manager->flush();
    }
}
