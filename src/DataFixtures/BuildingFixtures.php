<?php

namespace App\DataFixtures;

use App\Entity\Building;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BuildingFixtures extends Fixture
{
    public const BUILDING_1_REFERENCE = 'building-1';
    public const BUILDING_2_REFERENCE = 'building-2';

    public function load(ObjectManager $manager)
    {
        $building = new Building();
        $building
            ->setName('Building 1')
            ->setAddress('Rue des fleurs')
            ->setZipcode(26500)
            ->setCity('Bourg-lès-Valence');
        $manager->persist($building);
        $this->addReference(self::BUILDING_1_REFERENCE, $building);

        $building = new Building();
        $building
            ->setName('Building 2')
            ->setAddress('Rue des oiseaux')
            ->setZipcode(26000)
            ->setCity('Valence');
        $manager->persist($building);
        $this->addReference(self::BUILDING_2_REFERENCE, $building);

        $manager->flush();
    }
}
