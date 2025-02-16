<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\CommentLike;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentLikeTest extends TestCase
{
    private CommentLike $commentLike;

    protected function setUp(): void
    {
        $this->commentLike = new CommentLike();
    }

    public function testSetAndGetCreatedAt(): void
    {
        $date = new \DateTimeImmutable('2025-01-01 12:00:00');
        $this->commentLike->setCreatedAt($date);
        $this->assertEquals($date, $this->commentLike->getCreatedAt());
    }

    public function testSetAndGetUserId(): void
    {
        $user = new User();
        $user->setEmail('commentlikeuser@example.com')
             ->setFirstName('Comment')
             ->setLastName('Liker')
             ->setPassword('password'); // Pour le test, en clair (à hasher en production)

        $this->commentLike->setUserId($user);
        $this->assertSame($user, $this->commentLike->getUserId());
    }

    public function testSetAndGetCommentID(): void
    {
        $comment = new Comment();
        $comment->setContent('This is a sample comment.')
                ->setLevel(1)
                ->setRepliesCount(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        // Ici, on se contente de créer une instance minimale de Comment.
        $this->commentLike->setCommentID($comment);
        $this->assertSame($comment, $this->commentLike->getCommentID());
    }
}
