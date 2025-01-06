<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Vêtements' => [
                'icon' => 'tshirt',
                'children' => [
                    'Hauts' => [
                        'icon' => 'shirt',
                        'children' => [
                            'T-shirts' => ['icon' => 'tshirt'],
                            'Chemises' => ['icon' => 'shirt'],
                            'Pulls' => ['icon' => 'sweater'],
                        ]
                    ],
                    'Bas' => [
                        'icon' => 'pants',
                        'children' => [
                            'Pantalons' => ['icon' => 'pants'],
                            'Jeans' => ['icon' => 'jeans'],
                            'Jupes' => ['icon' => 'skirt'],
                        ]
                    ]
                ]
            ],
            'Accessoires' => [
                'icon' => 'accessories',
                'children' => [
                    'Bijoux' => ['icon' => 'jewelry'],
                    'Sacs' => ['icon' => 'bag'],
                    'Chapeaux' => ['icon' => 'hat']
                ]
            ]
        ];

        $this->createCategories($categories, null, $manager);
        $manager->flush();
    }

    private function createCategories(array $categories, ?Category $parent, ObjectManager $manager): void
    {
        foreach ($categories as $name => $data) {
            $category = new Category();
            $category->setName($name);
            $category->setIcon($data['icon'] ?? null);

            if ($parent) {
                $category->setParent($parent);
            }

            $manager->persist($category);

            if (isset($data['children'])) {
                $this->createCategories($data['children'], $category, $manager);
            }

            $this->addReference('category_'.strtolower(str_replace(' ', '_', $name)), $category);
        }
    }
}
