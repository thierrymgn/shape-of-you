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
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [
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

        $users = array_merge(
            ['user_admin', 'user_moderator'],
            array_map(function ($i) { return 'user_'.$i; }, range(1, 10))
        );

        foreach ($users as $userRef) {
            $usedCategories = []; // Pour suivre les catégories utilisées par utilisateur

            for ($i = 0; $i < 5; ++$i) {
                $item = new WardrobeItem();
                $item->setName($faker->words(3, true));
                $item->setDescription($faker->text());
                $item->setBrand($faker->company());
                $item->setSize($faker->randomElement(['XS', 'S', 'M', 'L', 'XL']));
                $item->setColor($faker->safeColorName);
                $item->setStatus(WardrobeStatus::ACTIVE);
                $item->setSeason(WardrobeSeason::ALL);
                $item->setImage($faker->imageUrl());

                do {
                    $randomCategory = $faker->randomElement($categories);
                } while (in_array($randomCategory, $usedCategories));

                $usedCategories[] = $randomCategory;
                $item->setCategory($this->getReference($randomCategory, Category::class));
                $item->setCustomer($this->getReference($userRef, User::class));

                $manager->persist($item);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
