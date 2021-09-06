<?php

namespace App\Tests\Unit\Entity;

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

        $contacts = new ArrayCollection();
        $contacts->add($contact);

        $site->addContact($contact);
        $this->assertEquals($contacts, $site->getContacts());


        // Documents collection
        $document = new Document();
        $document->setName('Doc');

        $documents = new ArrayCollection();
        $documents->add($document);

        $site->addDocument($document);
        $this->assertEquals($documents, $site->getDocuments());
    }
}
