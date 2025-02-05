<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\WardrobeItem;
use App\Entity\WardrobeItemTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WardrobeItemTagFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = array_merge(
            ['user_admin', 'user_moderator'],
            array_map(function ($i) { return 'user_'.$i; }, range(1, 10))
        );

        $allTags = [
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

        foreach ($users as $userRef) {
            for ($i = 0; $i < 5; ++$i) {
                $numberOfTags = $faker->numberBetween(2, 3);
                $selectedTags = $faker->randomElements($allTags, $numberOfTags);

                foreach ($selectedTags as $tagName) {
                    $wardrobeItemTag = new WardrobeItemTag();
                    $wardrobeItemTag->setWardrobeItem($this->getReference('wardrobe_item_'.$userRef.'_'.$i, WardrobeItem::class));
                    $wardrobeItemTag->setTag($this->getReference('tag_'.strtolower($tagName), Tag::class));
                    $manager->persist($wardrobeItemTag);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
            WardrobeItemFixtures::class,
        ];
    }
}
