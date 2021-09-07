<?php

namespace App\Tests\Api;

use App\Tests\_Base\ApiResourceTestCase;

class DocumentResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'document';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, null, null, 100);
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
        $this->profilerEnabled = true;
        $this->profilerQueryCount = 3;
        $this->profilerQueryDuration = 200;

        $this->expectedFields = [
            'type',
            'name',
            'title',
            'description',
            'language',
            'filename',
            'site',
            'createdAt',
            'updatedAt',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
//        self::$verbose = true;

        self::$postedData = [
            'type' => 'main',
            'name' => 'PdS',
            'title' => 'pds',
            'description' => 'This document is about',
        ];
        $this->expectedResponse = [
            "@context" => "/api/contexts/Document",
//            "@id" => "/api/documents/abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "@type" => "Document",
//            "id" => "abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "type" => "main",
            "name" => "PdS",
            "title" => "pds",
            "description" => "This document is about",
            "language" => "fr-FR",
            "filename" => null,
            "site" => null,
        ];

        parent::testCreateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }

    public function testUpdateResource()
    {
        $this->updateData = [
            'name' => 'PdS updated',
            'description' => 'This document is about...\nOne more line !',
        ];

        parent::testUpdateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }

    public function testDeleteResource()
    {
        parent::testDeleteResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }
}
