<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setFirstName('Fred')
            ->setLastName('Mohier')
            ->setEmail('fmohier@b2pweb.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'fmohier@b2pweb.com'
            ));
        $manager->persist($user);

        $user = new User();
        $user
            ->setFirstName('Gaston')
            ->setLastName('Lagaffe')
            ->setEmail('glagaffe@b2pweb.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'glagaffe@b2pweb.com'
            ));
        $manager->persist($user);

        $manager->flush();
    }
}
