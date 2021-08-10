<?php

namespace Unit\Entity;

use App\Entity\Building;
use App\Entity\Company;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    public function testConstruct()
    {
        $company = new Company();
        $this->assertEquals(1, $company->getId());
        $this->assertEquals('', $company->getName());
        $this->assertEquals('', $company->getBuilding());
    }

    public function testGettersSetters()
    {

        $building = new Building();
        $building->setName('A');

        $company = new Company();

        $company->setName('A');
        $this->assertEquals('A', $company->getName());

        $company->setBuilding($building);
        $this->assertEquals($building, $company->getBuilding());
    }
}
