<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $admin = $this->createUser(
            $faker,
            'admin@shapeyou.com',
            ['ROLE_ADMIN'],
            'admin'
        );
        $manager->persist($admin);
        $manager->persist($admin->getProfile());

        $moderator = $this->createUser(
            $faker,
            'moderator@shapeyou.com',
            ['ROLE_MODERATOR'],
            'moderator'
        );
        $manager->persist($moderator);
        $manager->persist($moderator->getProfile());

        for ($i = 0; $i < 20; $i++) {
            $user = $this->createUser(
                $faker,
                $faker->email(),
                ['ROLE_USER'],
                'password'
            );
            $manager->persist($user);
            $manager->persist($user->getProfile());
        }

        $manager->flush();
    }

    private function createUser($faker, $email, $roles, $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->lastName());
        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $user->setAvatar("https://ui-avatars.com/api/?name=" . urlencode($user->getFirstName() . "+" . $user->getLastName()));

        $profile = new Profile();
        $profile->setCustomer($user);
        $profile->setHeight($faker->randomFloat(2, 150, 200));
        $profile->setWeight($faker->randomFloat(2, 45, 120));
        $profile->setCreatedAt($now);
        $profile->setUpdatedAt($now);

        $bodyTypes = ['ectomorphe', 'mesomorphe', 'endomorphe', 'triangle', 'triangle inversé', 'sablier', 'rectangle'];
        $profile->setBodyType($faker->randomElement($bodyTypes));

        $stylePreferences = $faker->randomElements([
            'casual', 'business', 'sportswear', 'bohème',
            'minimaliste', 'streetwear', 'vintage', 'élégant',
            'rock', 'preppy', 'romantique'
        ], $faker->numberBetween(2, 5));

        $colorPreferences = $faker->randomElements([
            'noir', 'blanc', 'bleu marine', 'beige',
            'gris', 'marron', 'bordeaux', 'vert kaki',
            'bleu ciel', 'rose poudré'
        ], $faker->numberBetween(3, 6));

        $sizePreferences = [
            'haut' => $faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'bas' => $faker->randomElement(['36', '38', '40', '42', '44']),
            'chaussures' => $faker->randomElement(['36', '37', '38', '39', '40', '41', '42', '43', '44'])
        ];

        $profile->setStylePreferences($stylePreferences);
        $profile->setColorPreferences($colorPreferences);
        $profile->setSizePreferences($sizePreferences);

        $user->setProfile($profile);

        return $user;
    }
}
