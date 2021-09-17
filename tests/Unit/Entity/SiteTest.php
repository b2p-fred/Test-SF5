<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Contact;
use App\Entity\Document;
use App\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class SiteTest extends TestCase
{
    public function testConstruct()
    {
        $site = new Site();
        $this->assertInstanceOf(UuidV4::class, $site->getId());

        $this->assertEquals(null, $site->getCreatedAt());
        $this->assertEquals(null, $site->getUpdatedAt());

        $this->assertEquals(null, $site->getName());
        $this->assertEquals(null, $site->getTitle());
        $this->assertEquals(null, $site->getDescription());

        $emptyCollection = new ArrayCollection();
        $this->assertEquals($emptyCollection, $site->getContacts());
        $this->assertEquals($emptyCollection, $site->getDocuments());
        $this->assertEquals(null, $site->getMainAddress());
        $this->assertEquals(null, $site->getVehicleAddress());
    }

    public function testGettersSetters()
    {
        $site = new Site();

        $site->setName('A');
        $this->assertEquals('A', $site->getName());

        $site->setTitle('A');
        $this->assertEquals('A', $site->getTitle());

        $site->setDescription('A');
        $this->assertEquals('A', $site->getDescription());

        // Contacts collection
        $contact = new Contact();
        $contact->setFirstName('John');
        $contact->setLastName('Doe');
        $contact2 = new Contact();
        $contact2->setFirstName('Jack');
        $contact2->setLastName('Smith');

        $contacts = new ArrayCollection();
        $contacts->add($contact);
        $site->addContact($contact);
        $this->assertEquals($contacts, $site->getContacts());
        $this->assertEquals($contact->getSite(), $site);

        $contacts->add($contact2);
        $site->addContact($contact2);
        $this->assertEquals($contacts, $site->getContacts());
        $this->assertEquals($contact2->getSite(), $site);

        $contacts->removeElement($contact);
        $site->removeContact($contact);
        $this->assertEquals($contacts, $site->getContacts());
        $this->assertEquals($contact->getSite(), null);

        // Addresses
        $main = new Address();
        $vehicle = new Address();
        $site->setMainAddress($main);
        $site->setVehicleAddress($vehicle);
        $this->assertEquals($main, $site->getMainAddress());
        $this->assertEquals($vehicle, $site->getVehicleAddress());

        // Documents collection
        $document = new Document();
        $document->setName('Doc');

        $documents = new ArrayCollection();
        $documents->add($document);

        $site->addDocument($document);
        $this->assertEquals($documents, $site->getDocuments());
    }
}
