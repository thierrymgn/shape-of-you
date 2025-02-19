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

        // Création d'un utilisateur (customer)
        $customer = new User();
        $customer->setEmail('aianalysis@example.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword('password'); // Pour le test, en clair (mais à hasher en production)
        // Vous pouvez définir d'autres propriétés requises pour User ici...
        $manager->persist($customer);

        // Création d'un Outfit pour l'analyse de type OUTFIT
        $outfit = new Outfit();
        // Exemple d'initialisation minimaliste pour l'Outfit
        $outfit->setName('Summer Look')
            ->setOccasion('Casual')
            ->setSeason(WardrobeSeason::SUMMER) // Assurez-vous que WardrobeSeason::SUMMER existe
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCustomer($customer)
            ->setPublic(true); // Remplit le champ isPublic

        // Vous pouvez ajouter d'autres propriétés requises ici
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
            ->setOutfitId($outfit); // Association avec l'Outfit créé
        $manager->persist($analysisOutfit);

        // Création de l'analyse pour un wardrobe item (vêtement)
        $analysisItem = new AiAnalysis();
        $analysisItem->setAnalysisType(AnalysisType::ITEM)
            ->setResults([
                'color' => 'red',
                'fit' => 'regular',
            ])
            ->setConfidenceScore('88.75')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setWardrobeItemId($wardrobeItem); // Association avec le WardrobeItem créé
        $manager->persist($analysisItem);

        $manager->flush();
    }
}
