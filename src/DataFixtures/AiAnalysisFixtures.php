<?php

namespace App\DataFixtures;

use App\Entity\AiAnalysis;
use App\Entity\Category;
use App\Entity\Outfit;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\AnalysisType;
use App\Enum\WardrobeSeason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AiAnalysisFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customer = new User();
        $customer->setEmail('customer@example.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword('password');
        $manager->persist($customer);

        $outfit = new Outfit();
        $outfit->setName('Summer Look')
            ->setOccasion('Casual')
            ->setSeason(WardrobeSeason::SUMMER)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCustomer($customer)
            ->setPublic(true);

        $manager->persist($outfit);

        $category = new Category();
        $category->setName('Default Category');
        $manager->persist($category);

        $wardrobeItem = new WardrobeItem();

        $wardrobeItem->setName('Basic T-Shirt')
                     ->setSize('M')
                     ->setColor('Blue')
                     ->setCreatedAt(new \DateTimeImmutable())
                     ->setUpdatedAt(new \DateTimeImmutable())
                     ->setCustomer($customer)
                     ->setCategory($category);

        $manager->persist($wardrobeItem);

        $analysisOutfit = new AiAnalysis();
        $analysisOutfit->setAnalysisType(AnalysisType::OUTFIT)
            ->setResults([
                'style' => 'casual',
                'mood' => 'happy',
            ])
            ->setConfidenceScore('92.50')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOutfitId($outfit);
        $manager->persist($analysisOutfit);

        $analysisItem = new AiAnalysis();
        $analysisItem->setAnalysisType(AnalysisType::ITEM)
            ->setResults([
                'color' => 'red',
                'fit' => 'regular',
            ])
            ->setConfidenceScore('88.75')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setWardrobeItemId($wardrobeItem);
        $manager->persist($analysisItem);

        $manager->flush();
    }
}
