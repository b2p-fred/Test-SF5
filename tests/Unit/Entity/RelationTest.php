<?php

namespace App\Tests\Unit\Entity;

use App\DBAL\Types\RelationType;
use App\Entity\DocumentVersion;
use App\Entity\Relation;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class RelationTest extends TestCase
{
    public function testConstruct()
    {
        $relation = new Relation();
        $this->assertInstanceOf(UuidV4::class, $relation->getId());

        $this->assertEquals(RelationType::RELATION_CREATED, $relation->getType());

        // $this->assertNotEmpty($relation->getSender());
        // $this->assertNotEmpty($relation->getRecipient());

        // $this->assertNotEmpty($relation->getCreatedAt());
        // $this->assertNotEmpty($relation->getUpdatedAt());
    }

    public function testGettersSetters()
    {
        $user = new User();
        $user->setFirstName('A');

        $user2 = new User();
        $user2->setFirstName('B');

        $protocol = new DocumentVersion();

        $relation = new Relation();

        $relation->setType(RelationType::RELATION_SENT);
        $this->assertEquals(RelationType::RELATION_SENT, $relation->getType());

        $relation->setSender($user);
        $this->assertEquals($user, $relation->getSender());
        $relation->setRecipient($user2);
        $this->assertEquals($user2, $relation->getRecipient());

        $relation->setProtocol($protocol);
        $this->assertEquals($protocol, $relation->getProtocol());

        $relation->setSender($user);
        $this->assertEquals($user, $relation->getSender());
    }
}
