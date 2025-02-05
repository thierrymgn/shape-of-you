<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
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
        $this->addReference('user_admin', $admin);

        $moderator = $this->createUser(
            $faker,
            'moderator@shapeyou.com',
            ['ROLE_MODERATOR'],
            'moderator'
        );
        $manager->persist($moderator);
        $manager->persist($moderator->getProfile());
        $this->addReference('user_moderator', $moderator);

        for ($i = 0; $i <= 10; ++$i) {
            $user = $this->createUser(
                $faker,
                $faker->email(),
                ['ROLE_USER'],
                'password'
            );
            $manager->persist($user);
            $manager->persist($user->getProfile());
            $this->addReference('user_'.$i, $user);
        }

        $manager->flush();
    }

    /** @param array<string> $roles */
    private function createUser(
        Generator $faker,
        string $email,
        array $roles,
        string $password,
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->lastName());

        $user->setAvatar('https://ui-avatars.com/api/?name='.urlencode($user->getFirstName().'+'.$user->getLastName()));

        $profile = new Profile();
        $profile->setCustomer($user);
        $profile->setHeight((string) $faker->randomFloat(2, 150, 200));
        $profile->setWeight((string) $faker->randomFloat(2, 45, 120));

        $bodyTypes = ['ectomorphe', 'mesomorphe', 'endomorphe', 'triangle', 'triangle inversé', 'sablier', 'rectangle'];
        $profile->setBodyType($faker->randomElement($bodyTypes));

        $stylePreferences = $faker->randomElements([
            'casual', 'business', 'sportswear', 'bohème',
            'minimaliste', 'streetwear', 'vintage', 'élégant',
            'rock', 'preppy', 'romantique',
        ], $faker->numberBetween(2, 5));

        $colorPreferences = $faker->randomElements([
            'noir', 'blanc', 'bleu marine', 'beige',
            'gris', 'marron', 'bordeaux', 'vert kaki',
            'bleu ciel', 'rose poudré',
        ], $faker->numberBetween(3, 6));

        $sizePreferences = [
            'haut' => $faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'bas' => $faker->randomElement(['36', '38', '40', '42', '44']),
            'chaussures' => $faker->randomElement(['36', '37', '38', '39', '40', '41', '42', '43', '44']),
        ];

        $profile->setStylePreferences($stylePreferences);
        $profile->setColorPreferences($colorPreferences);
        $profile->setSizePreferences($sizePreferences);

        $user->setProfile($profile);

        return $user;
    }
}
