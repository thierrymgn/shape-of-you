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
use Faker\Generator;

class OutfitFixtures extends Fixture implements DependentFixtureInterface
{
    private const OUTFITS_PER_USER = 3;
    private const MIN_ITEMS_PER_OUTFIT = 3;
    private const MAX_ITEMS_PER_OUTFIT = 5;
    private const OCCASIONS = ['Casual', 'Sport', 'Business', 'Party'];
    private const WARDROBE_ITEMS_RANGE = [0, 4];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach ($this->getUserReferences() as $userRef) {
            $this->createOutfitsForUser($manager, $faker, $userRef);
        }

        $manager->flush();
    }

    /**
     * @return array<string>
     */
    private function getUserReferences(): array
    {
        return [
            'user_admin',
            'user_moderator',
            ...array_map(fn ($i): string => 'user_'.$i, range(1, 10)),
        ];
    }

    private function createOutfitsForUser(ObjectManager $manager, Generator $faker, string $userRef): void
    {
        for ($i = 0; $i < self::OUTFITS_PER_USER; ++$i) {
            $outfit = $this->createOutfit($faker, $userRef);
            $manager->persist($outfit);

            $this->addItemsToOutfit($manager, $faker, $outfit, $userRef);
        }
    }

    private function createOutfit(Generator $faker, string $userRef): Outfit
    {
        return (new Outfit())
            ->setName($faker->words(3, true))
            ->setDescription($faker->text())
            ->setOccasion($faker->randomElement(self::OCCASIONS))
            ->setSeason($faker->randomElement(WardrobeSeason::getSeasons()))
            ->setPublic($faker->boolean(80))
            ->setImage($faker->imageUrl())
            ->setCustomer($this->getReference($userRef, User::class));
    }

    private function addItemsToOutfit(ObjectManager $manager, Generator $faker, Outfit $outfit, string $userRef): void
    {
        $itemCount = $faker->numberBetween(self::MIN_ITEMS_PER_OUTFIT, self::MAX_ITEMS_PER_OUTFIT);

        for ($position = 1; $position <= $itemCount; ++$position) {
            $wardrobeItem = $this->getReference(
                'wardrobe_item_'.$userRef.'_'.$faker->numberBetween(...self::WARDROBE_ITEMS_RANGE),
                WardrobeItem::class
            );

            $manager->persist(
                (new OutfitItem())
                    ->setOutfit($outfit)
                    ->setPosition($position)
                    ->setWardrobeItem($wardrobeItem)
            );
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            WardrobeItemFixtures::class,
        ];
    }
}
