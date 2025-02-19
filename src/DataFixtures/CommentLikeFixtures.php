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
        $user = new User();
        $user->setEmail('commentlikeuser@example.com')
             ->setFirstName('Comment')
             ->setLastName('Liker')
             ->setPassword('password');
        $manager->persist($user);

        $post = new SocialPost();
        $post->setTitle('Post for Comment')
             ->setContent('This is a sample social post used for comments.')
             ->setImage('https://example.com/post.jpg')
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($post);

        $comment = new Comment();
        $comment->setContent('This is a sample comment.')
                ->setLevel(1)
                ->setRepliesCount(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setUserId($user)
                ->setPostId($post);
        $manager->persist($comment);

        $commentLike = new CommentLike();
        $commentLike->setCreatedAt(new \DateTimeImmutable())
                    ->setUserId($user)
                    ->setCommentID($comment);
        $manager->persist($commentLike);

        $manager->flush();
    }
}
