<?php

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\ContactType;
use App\Entity\Contact;
use App\Entity\Site;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class ContactTest extends TestCase
{
    public function testConstruct()
    {
        $contact = new Contact();
        $this->assertInstanceOf(UuidV4::class, $contact->getId());

        $this->assertEquals(null, $contact->getFirstName());
        $this->assertEquals(null, $contact->getLastName());
        $this->assertEquals(null, $contact->getEmail());
        $this->assertEquals(ContactType::CONTACT, $contact->getType());
        $this->assertEquals(null, $contact->getIdentifier());
        $this->assertEquals(null, $contact->getPassword());
        $this->assertEquals(null, $contact->getPhone());
        $this->assertEquals('fr-FR', $contact->getLanguage());
//        $this->assertEquals($site, $contact->getSite());
    }

    public function testGettersSetters()
    {
        $contact = new contact();

        $contact->setFirstName('A');
        $this->assertEquals('A', $contact->getFirstName());

        $contact->setLastName('A');
        $this->assertEquals('A', $contact->getLastName());

        $contact->setEmail('A');
        $this->assertEquals('A', $contact->getEmail());

        $contact->setType(ContactType::CONTACT);
        $this->assertEquals(ContactType::CONTACT, $contact->getType());
        $contact->setType(ContactType::CONTACT_VISITOR);
        $this->assertEquals(ContactType::CONTACT_VISITOR, $contact->getType());
        $contact->setType(ContactType::CONTACT_EMERGENCY);
        $this->assertEquals(ContactType::CONTACT_EMERGENCY, $contact->getType());
        $contact->setType(ContactType::CONTACT_RESPONSIBLE);
        $this->assertEquals(ContactType::CONTACT_RESPONSIBLE, $contact->getType());

        $contact->setIdentifier('A');
        $this->assertEquals('A', $contact->getIdentifier());

        $contact->setPassword('A');
        $this->assertEquals('A', $contact->getPassword());

        $contact->setPhone('A');
        $this->assertEquals('A', $contact->getPhone());

        $contact->setLanguage('A');
        $this->assertEquals('A', $contact->getLanguage());

        $site = new Site();
        $contact->setSite($site);
        $this->assertEquals($site, $contact->getSite());
    }
}
