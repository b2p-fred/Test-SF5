<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Building;
use App\Entity\Company;
use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class BuildingTest extends TestCase
{
    public function testConstruct()
    {
        $building = new Building();
        $this->assertInstanceOf(UuidV4::class, $building->getId());
        $companies = new ArrayCollection();
        $this->assertEquals($companies, $building->getCompanies());
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

        $building->setZipcode('26000');
        $this->assertEquals('26000', $building->getZipcode());
    }

    public function testABuilding()
    {
        $company = new Company();
        $company->setName('Acme');

        $building = new Building();

        $building->setName('A');
        $this->assertEquals('A', $building->getName());

        $building->setAddress('A');
        $this->assertEquals('A', $building->getAddress());

        $building->setCity('A');
        $this->assertEquals('A', $building->getCity());

        $building->setZipcode('26000');
        $this->assertEquals('26000', $building->getZipcode());

        // Companies are collections !
        $companies = new ArrayCollection();
        $companies->add($company);

        $building->addCompany($company);
        $this->assertEquals($companies, $building->getCompanies());

        $building->removeCompany($company);
        $companies->removeElement($company);
        $this->assertEquals($companies, $building->getCompanies());
    }
}
