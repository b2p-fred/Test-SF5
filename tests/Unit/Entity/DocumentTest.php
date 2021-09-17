<?php

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\DocumentType;
use App\Entity\Document;
use App\Entity\DocumentVersion;
use App\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class DocumentTest extends TestCase
{
    public function testConstruct()
    {
        $document = new Document();
        $this->assertInstanceOf(UuidV4::class, $document->getId());

        $this->assertEquals(null, $document->getCreatedAt());
        $this->assertEquals(null, $document->getUpdatedAt());

        $this->assertEquals(null, $document->getName());
        $this->assertEquals(null, $document->getTitle());
        $this->assertEquals(null, $document->getDescription());
        $this->assertEquals(DocumentType::DOCUMENT_PROTOCOL, $document->getType());
        $this->assertEquals('fr-FR', $document->getLanguage());
        $this->assertEquals(null, $document->getFilename());
//        $this->assertEquals(null, $document->getSite());
        $this->assertEquals(0, $document->getVersion());
        $this->assertEquals(null, $document->getVersionedAt());
    }

    public function testGettersSetters()
    {
        $document = new document();

        $document->setName('A');
        $this->assertEquals('A', $document->getName());

        $document->setTitle('A');
        $this->assertEquals('A', $document->getTitle());

        $document->setDescription('A');
        $this->assertEquals('A', $document->getDescription());

        $document->setType(DocumentType::DOCUMENT_PROTOCOL);
        $this->assertEquals(DocumentType::DOCUMENT_PROTOCOL, $document->getType());
        $document->setType(DocumentType::DOCUMENT_ANNEX);
        $this->assertEquals(DocumentType::DOCUMENT_ANNEX, $document->getType());

        $document->setFilename('A');
        $this->assertEquals('A', $document->getFilename());

        $document->setLanguage('A');
        $this->assertEquals('A', $document->getLanguage());

        $document->setVersion(1);
        $this->assertEquals(1, $document->getVersion());

        // fixme should stub the php time functions for this test...
        // Using the uopz extension with the ClockMock package should allow this!
//        $now = new \DateTime();
//        $this->assertEquals($now, $document->getVersionedAt());

        // Site relation
        $site = new Site();
        $document->setSite($site);
        $this->assertEquals($site, $document->getSite());

        // Version relation
        $documentVersion = new DocumentVersion();
        $dvCollection = new ArrayCollection();
        $this->assertEquals($dvCollection, $document->getVersions());

        $dvCollection->add($documentVersion);
        $document->addVersion($documentVersion);
        $this->assertEquals($dvCollection, $document->getVersions());

        // It is not possible to remove a document attached to a version!
        // Remove the version first if needed...
        // $dvCollection->removeElement($documentVersion);
        // $document->removeVersion($documentVersion);
        // $this->assertEquals($dvCollection, $document->getVersions());
    }
}
