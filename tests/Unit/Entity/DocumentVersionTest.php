<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Document;
use App\Entity\DocumentVersion;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class DocumentVersionTest extends TestCase
{
    public function testConstruct()
    {
        $documentVersion = new DocumentVersion();
        $this->assertInstanceOf(UuidV4::class, $documentVersion->getId());

        // $this->assertNotEmpty($documentVersion->getCreatedAt());
        // $this->assertNotEmpty($documentVersion->getUpdatedAt());
    }

    public function testGettersSetters()
    {
        $documentVersion = new DocumentVersion();

        $document = new Document();

        $documentVersion->setDescription('A');
        $this->assertEquals('A', $documentVersion->getDescription());

        $documentVersion->setVersion(1);
        $this->assertEquals(1, $documentVersion->getVersion());

        $user = new User();
        $documentVersion->setInitiator($user);
        $this->assertEquals($user, $documentVersion->getInitiator());

        // Creates the relation between the document and its version
        $document->addVersion($documentVersion);

        // $documentVersion->setDocument($document);
        $this->assertEquals($document, $documentVersion->getDocument());
    }
}
