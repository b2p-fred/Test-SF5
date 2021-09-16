<?php

namespace App\Tests\Api;

use App\Entity\Document;
use App\Entity\User;
use App\Tests\_Base\ApiResourceTestCase;

class DocumentVersionResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'document_version';

    public static function setUpBeforeClass(): void
    {
        parent::configure(self::RESOURCE, 'DocumentVersion', null, null, 100);
    }

    /**
     * Resource access is denied when not authenticated.
     */
    public function testDenied()
    {
        parent::testDenied();
    }

    public function testListResource(array $expectedFields = [])
    {
        $this->profilerEnabled = true;
        $this->profilerQueryCount = 13;
        $this->profilerQueryDuration = 200;

        $this->expectedFields = [
            '@id',
            '@type',
            'id',
            'document',
            'version',
            'description',
            'initiator',
            'createdAt',
            'updatedAt',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        $client = static::createAuthenticatedClient();

        // Get the first document in the database
        $response = $client->request('GET', 'api/documents');
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        /* @var $firstDocument Document */
        $firstDocument = $json['hydra:member'][0];
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', a document: '.PHP_EOL;
            echo json_encode($firstDocument, JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
        }

        // Get the first user in the database
        $response = $client->request('GET', 'api/users');
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        /* @var $firstUser User */
        $firstUser = $json['hydra:member'][0];
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', a document: '.PHP_EOL;
            echo json_encode($firstUser, JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
        }

        self::$postedData = [
            'document' => $firstDocument['@id'],
            'version' => 99,
            'description' => "A version for testing!",
            'initiator' => $firstUser['@id'],
        ];
        $this->expectedResponse = [
            "@context" => "/api/contexts/DocumentVersion",
            // "@id" => "/api/document_versions/7cd73e63-84a8-4f65-8960-cb056916bd0a",
            "@type" => "DocumentVersion",
            // "id" => "7cd73e63-84a8-4f65-8960-cb056916bd0a",
            "document" => $firstDocument['@id'],
            "version" => 99,
            "description" => "A version for testing!",
            "initiator" => $firstUser['@id'],
            // "createdAt" => "2021-09-16T13=>35=>26+00=>00",
            // "updatedAt" => "2021-09-16T13:35:26+00:00"
        ];

        parent::testCreateResource();
    }
}
