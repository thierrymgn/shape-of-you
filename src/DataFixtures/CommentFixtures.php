<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $comments = [];

        // Générer 50 commentaires "parents"
        for ($i = 0; $i < 50; ++$i) {
            $comment = new Comment();
            $comment
                ->setContent($faker->sentence(10))
                ->setLevel(1)
                ->setRepliesCount(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($comment);
            $comments[] = $comment;
        }

        for ($i = 0; $i < 50; ++$i) {
            $parentComment = $faker->randomElement($comments);

            $reply = new Comment();
            $reply
                ->setContent($faker->sentence(10))
                ->setLevel($parentComment->getLevel() + 1)
                ->setRepliesCount(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            if (method_exists($reply, 'setParentComment')) {
                $reply->setParentComment($parentComment);
            }

            $parentComment->setRepliesCount($parentComment->getRepliesCount() + 1);

            $manager->persist($reply);
        }
        $manager->flush();
    }
}
