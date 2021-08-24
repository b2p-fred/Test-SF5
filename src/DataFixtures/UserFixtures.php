<?php

namespace App\DataFixtures;

use App\DBAL\Types\HumanGenderType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user = new User();
        $user
            ->setFirstName('Big')
            ->setLastName('Brother')
            ->setEmail('big.brother@theworld.com')
            ->setGender(HumanGenderType::GENDER_MALE)
            ->setBirthdate(new \DateTime('01/06/1966'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'I@mTh3B0ss!'
            ));
        $manager->persist($user);

        $user = new User();
        $user
            ->setFirstName('Gaston')
            ->setLastName('Lagaffe')
            ->setGender(HumanGenderType::GENDER_UNKNOWN)
            ->setBirthdate(new \DateTime('01/02/1957'))
            ->setEmail('gaston.lagaffe@edition-dupuis.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'Gaston!'
            ));
        $manager->persist($user);

        $manager->flush();
    }
}
