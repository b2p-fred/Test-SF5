<?php

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\DocumentType;
use App\Entity\Document;
use App\Entity\Site;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class DocumentTest extends TestCase
{
    public function testConstruct()
    {
        $document = new Document();
        $this->assertInstanceOf(UuidV4::class, $document->getId());

        $this->assertEquals(null, $document->getName());
        $this->assertEquals(null, $document->getTitle());
        $this->assertEquals(null, $document->getDescription());
        $this->assertEquals(DocumentType::DOCUMENT_MAIN, $document->getType());
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

        $document->setType(DocumentType::DOCUMENT_MAIN);
        $this->assertEquals(DocumentType::DOCUMENT_MAIN, $document->getType());
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

        $site = new Site();
        $document->setSite($site);
        $this->assertEquals($site, $document->getSite());
    }
}
