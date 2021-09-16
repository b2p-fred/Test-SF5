<?php

namespace App\Tests\Api;

use App\Entity\Document;
use App\Entity\DocumentVersion;
use App\Entity\User;
use App\Tests\_Base\ApiResourceTestCase;

class RelationResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'relation';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, null, null, 12);
    }

    /**
     * Resource access is denied when not authenticated.
     */
    public function testDenied()
    {
        parent::testDenied();
    }

    public function testListResource()
    {
        self::$verbose = false;

        $this->profilerEnabled = true;
        $this->profilerQueryCount = 13;
        $this->profilerQueryDuration = 200;

        $this->expectedFields = [
            '@id',
            '@type',
            'id',
            'type',
            'sender',
            'recipient',
            'protocol',
            'comments',
            'createdAt',
            'updatedAt',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        //self::$verbose = true;

        $client = static::createAuthenticatedClient();

        // Get the first document version in the database
        $response = $client->request('GET', 'api/document_versions');
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        /* @var $firstDocumentVersion DocumentVersion */
        $firstDocumentVersion = $json['hydra:member'][0];
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', a document: '.PHP_EOL;
            echo json_encode($firstDocumentVersion, JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
        }

        // Get the first user in the database
        $response = $client->request('GET', 'api/users');
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        /* @var $firstUser User */
        $firstUser = $json['hydra:member'][0];
        /* @var $secondtUser User */
        $secondUser = $json['hydra:member'][0];
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', a document: '.PHP_EOL;
            echo json_encode($firstUser, JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
            echo PHP_EOL.'----- '.__METHOD__.', a document: '.PHP_EOL;
            echo json_encode($secondUser, JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
        }

        self::$postedData = [
            'sender' => $firstUser['@id'],
            'recipient' => $secondUser['@id'],
            'protocol' => $firstDocumentVersion['@id'],
            'comments' => "A version for testing!",
        ];
        $this->expectedResponse = [
            "@context" => "/api/contexts/Relation",
            // "@id" => "\/api\/relations\/90c1948f-9c5a-4f80-944f-337bab1fbfac",
            "@type" => "Relation",
            // "id" => "90c1948f-9c5a-4f80-944f-337bab1fbfac",
            "type" => "created",
            "sender" => $firstUser['@id'],
            "recipient" => $secondUser['@id'],
            "protocol" => $firstDocumentVersion['@id'],
            "comments" => "A version for testing!",
            // "createdAt" => "2021-09-16T13=>42=>35+00=>00",
            // "updatedAt" => "2021-09-16T13=>42=>35+00=>00",
        ];

        parent::testCreateResource();
    }

}
