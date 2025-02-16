<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PostLike;
use App\Entity\SocialPost;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostLikeTest extends TestCase
{
    private PostLike $postLike;

    protected function setUp(): void
    {
        $this->postLike = new PostLike();
    }

    public function testSetAndGetCreatedAt(): void
    {
        $createdAt = new \DateTimeImmutable('2025-01-01 12:00:00');
        $this->postLike->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->postLike->getCreatedAt());
    }

    public function testSetAndGetUserId(): void
    {
        $user = new User();
        $user->setEmail('likeuser@example.com')
             ->setFirstName('Like')
             ->setLastName('User')
             ->setPassword('password');

        $this->postLike->setUserId($user);
        $this->assertSame($user, $this->postLike->getUserId());
    }

    public function testSetAndGetPostId(): void
    {
        $socialPost = new SocialPost();
        $socialPost->setTitle('Sample Social Post')
                   ->setContent('This is a sample post content.')
                   ->setImage('https://example.com/sample.jpg')
                   ->setCreatedAt(new \DateTimeImmutable('2025-01-01 12:00:00'))
                   ->setUpdatedAt(new \DateTimeImmutable('2025-01-01 12:00:00'));

        $this->postLike->setPostId($socialPost);
        $this->assertSame($socialPost, $this->postLike->getPostId());
    }
}
