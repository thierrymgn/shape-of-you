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
        $user = new User();
        $user->setEmail('likeuser@example.com')
             ->setFirstName('Like')
             ->setLastName('User')
             ->setPassword('password');
        $manager->persist($user);

        $post = new SocialPost();
        $post->setTitle('Sample Social Post')
             ->setContent('This is a sample post content.')
             ->setImage('https://example.com/sample.jpg')
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($post);

        $postLike = new PostLike();
        $postLike->setCreatedAt(new \DateTimeImmutable())
                 ->setUserId($user)
                 ->setPostId($post);
        $manager->persist($postLike);

        $manager->flush();
    }
}
