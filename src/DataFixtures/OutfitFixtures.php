<?php

namespace App\DataFixtures;

use App\Entity\Outfit;
use App\Entity\OutfitItem;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeSeason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OutfitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = array_merge(
            ['user_admin', 'user_moderator'],
            array_map(function ($i) { return 'user_'.$i; }, range(1, 10))
        );

        foreach ($users as $userRef) {
            for ($i = 0; $i < 3; ++$i) {
                $outfit = new Outfit();
                $outfit->setName($faker->words(3, true));
                $outfit->setDescription($faker->text());
                $outfit->setOccasion($faker->randomElement(['Casual', 'Sport', 'Business', 'Party']));
                $outfit->setSeason($faker->randomElement(WardrobeSeason::getSeasons()));
                $outfit->setPublic($faker->boolean(80));
                $outfit->setImage($faker->imageUrl());
                $outfit->setCustomer($this->getReference($userRef, User::class));

                $manager->persist($outfit);

                $itemCount = $faker->numberBetween(3, 5);
                for ($j = 0; $j < $itemCount; ++$j) {
                    $outfitItem = new OutfitItem();
                    $outfitItem->setOutfit($outfit);
                    $outfitItem->setPosition($j + 1);

                    $randomItemIndex = $faker->numberBetween(0, 4);
                    $outfitItem->setWardrobeItem($this->getReference('wardrobe_item_'.$userRef.'_'.$randomItemIndex, WardrobeItem::class));

                    $manager->persist($outfitItem);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            WardrobeItemFixtures::class,
        ];
    }
}
