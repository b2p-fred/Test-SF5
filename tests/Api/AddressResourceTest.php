<?php

namespace App\Tests\Api;

use App\Entity\Address;
use App\Tests\_Base\ApiResourceTestCase;

class AddressResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'address';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, 'addresses', null, 100);
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
        $this->profilerQueryCount = 23; // note that it may be much
        $this->profilerQueryDuration = 200;

        $this->expectedFields = [
            'type',
            'address',
            'address2',
            'zipcode',
            'city',
            'country',
            'lat',
            'lng',
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
            'address' => 'Rue des Fleurs',
            'zipcode' => '26000',
            'city' => 'Valence',
        ];
        $this->expectedResponse = [
            "@context" => "/api/contexts/Address",
//            "@id" => "/api/addresses/abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "@type" => "Address",
//            "id" => "abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "type" => "main",
            'address' => 'Rue des Fleurs',
            'zipcode' => '26000',
            'city' => 'Valence',
            "country" => null,
            "lat" => Address::DEFAULT_LAT,
            "lng" => Address::DEFAULT_LNG,
            "site" => null,
        ];

        parent::testCreateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }

    public function testUpdateResource()
    {
        $this->updateData = [
            'address2' => 'Au début...',
            'country' => 'France',
            'lat' => 44.945974,
            'lng' => 4.895834
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
