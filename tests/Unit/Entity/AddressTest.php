<?php

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\AddressType;
use App\DBAL\Types\ContactType;
use App\Entity\Address;
use App\Entity\Company;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use function Sodium\add;

class AddressTest extends TestCase
{
    public function testConstruct()
    {
        $address = new Address();
        $this->assertInstanceOf(UuidV4::class, $address->getId());

        $this->assertEquals(AddressType::ADDRESS_MAIN, $address->getType());

        $this->assertEquals(null, $address->getAddress());
        $this->assertEquals(null, $address->getAddress2());
        $this->assertEquals(null, $address->getZipcode());
        $this->assertEquals(null, $address->getCity());
        $this->assertEquals(null, $address->getCountry());
        $this->assertEquals(Address::DEFAULT_LAT, $address->getLat());
        $this->assertEquals(Address::DEFAULT_LNG, $address->getLng());
    }

    public function testGettersSetters()
    {
        $address = new Address();

        $address->setType(AddressType::ADDRESS_MAIN);
        $this->assertEquals(AddressType::ADDRESS_MAIN, $address->getType());
        $address->setType(AddressType::ADDRESS_VEHICLE);
        $this->assertEquals(AddressType::ADDRESS_VEHICLE, $address->getType());

        $address->setAddress('A');
        $this->assertEquals('A', $address->getAddress());
        $address->setAddress2('A');
        $this->assertEquals('A', $address->getAddress2());

        $address->setZipcode('26000');
        $this->assertEquals('26000', $address->getZipcode());

        $address->setCountry('A');
        $this->assertEquals('A', $address->getCountry());

        $address->setCity('A');
        $this->assertEquals('A', $address->getCity());

        $address->setLat(40.123456);
        $this->assertEquals(40.123456, $address->getLat());
        $address->setLng(40.123456);
        $this->assertEquals(40.123456, $address->getLng());
    }
}
