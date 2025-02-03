<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $tags = [
            'Casual',
            'Business',
            'Sportwear',
            'Summer',
            'Winter',
            'Party',
            'Vintage',
            'Streetwear',
            'Elegant',
            'Bohemian',
            'Chic',
            'Rock',
            'Gothic',
            'Romantic',
            'Minimalist',
            'Preppy',
            'Grunge',
            'Hipster',
            'Punk',
            'Retro',
            'Urban',
            'Classic',
            'Trendy',
            'Artsy',
            'Edgy',
            'Sophisticated',
            'Eclectic',
        ];

        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            $this->addReference('tag_'.strtolower($tagName), $tag);
        }

        $manager->flush();
    }
}
