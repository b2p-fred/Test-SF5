<?php

namespace Unit\Entity;

use App\Entity\Building;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{
    public function testConstruct()
    {
        $building = new Building();
        $this->assertEquals('', $building->getId());
        $this->assertEquals('', $building->getName());
        $this->assertEquals('', $building->getAddress());
        $this->assertEquals('', $building->getCity());
        $this->assertEquals('', $building->getZipcode());
    }

    public function testGettersSetters()
    {
        $building = new Building();

        $building->setName('A');
        $this->assertEquals('A', $building->getName());

        $building->setAddress('A');
        $this->assertEquals('A', $building->getAddress());

        $building->setCity('A');
        $this->assertEquals('A', $building->getCity());

        $building->setZipcode(1);
        $this->assertEquals(1, $building->getZipcode());
    }
}
