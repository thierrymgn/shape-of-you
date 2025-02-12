<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private Comment $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testBasicProperties(): void
    {
        $this->comment
            ->setContent('This is a comment')
            ->setLevel(1)
            ->setRepliesCount(0);

        $this->assertEquals('This is a comment', $this->comment->getContent());
        $this->assertEquals(1, $this->comment->getLevel());
        $this->assertEquals(0, $this->comment->getRepliesCount());
    }

    public function testTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->comment->setCreatedAt($now);

        $this->assertEquals($now, $this->comment->getCreatedAt());
    }
}
