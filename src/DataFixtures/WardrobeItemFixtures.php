<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WardrobeItemFixtures extends Fixture implements DependentFixtureInterface
{
    private const ITEMS_PER_USER = 5;
    private const SIZES = ['XS', 'S', 'M', 'L', 'XL'];
    private const CATEGORY_REFERENCES = [
        'category_t-shirts',
        'category_chemises',
        'category_pulls',
        'category_hauts',
        'category_pantalons',
        'category_jeans',
        'category_jupes',
        'category_bas',
        'category_vêtements',
        'category_bijoux',
        'category_sacs',
        'category_chapeaux',
        'category_accessoires',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach ($this->getUserReferences() as $userRef) {
            $this->createUserItems($manager, $faker, $userRef);
        }

        $manager->flush();
    }

    private function getUserReferences(): array
    {
        return [
            'user_admin',
            'user_moderator',
            ...array_map(fn($i) => 'user_'.$i, range(1, 10)),
        ];
    }

    private function createUserItems(ObjectManager $manager, \Faker\Generator $faker, string $userRef): void
    {
        $categories = $faker->randomElements(self::CATEGORY_REFERENCES, self::ITEMS_PER_USER);

        foreach ($categories as $index => $categoryRef) {
            $item = $this->createWardrobeItem($faker, $userRef, $categoryRef);
            $manager->persist($item);
            $this->addReference("wardrobe_item_{$userRef}_{$index}", $item);
        }
    }

    private function createWardrobeItem(\Faker\Generator $faker, string $userRef, string $categoryRef): WardrobeItem
    {
        return (new WardrobeItem())
            ->setName($faker->words(3, true))
            ->setDescription($faker->text())
            ->setBrand($faker->company())
            ->setSize($faker->randomElement(self::SIZES))
            ->setColor($faker->safeColorName)
            ->setStatus(WardrobeStatus::ACTIVE)
            ->setSeason($faker->randomElement(WardrobeSeason::getSeasons()))
            ->setImage($faker->imageUrl())
            ->setCategory($this->getReference($categoryRef, Category::class))
            ->setCustomer($this->getReference($userRef, User::class));
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
