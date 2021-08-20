<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Building;
use App\Entity\Company;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class CompanyTest extends TestCase
{
    public function testConstruct()
    {
        $company = new Company();
        $this->assertInstanceOf(UuidV4::class, $company->getId());
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
