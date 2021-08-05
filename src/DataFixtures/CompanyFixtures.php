<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company
            ->setName('Acme 1')
            ->setBuilding($this->getReference(BuildingFixtures::BUILDING_1_REFERENCE));
        $manager->persist($company);

        $manager->flush();
    }
}
