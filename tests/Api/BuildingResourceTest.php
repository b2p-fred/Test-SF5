<?php

namespace App\Tests\Api;

use App\Tests\_Base\ApiResourceTestCase;

class BuildingResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'building';

    public static function setUpBeforeClass(): void
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

    public function testListResource(array $expectedFields = [])
    {
        $this->profilerEnabled = true;
        $this->profilerQueryCount = 13;
        $this->profilerQueryDuration = 200;

        $this->expectedFields = [
            'name',
            'address',
            'zipcode',
            'city',
            'companies',
            'lat',
            'lng',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        self::$postedData = [
            'name' => 'New item',
            'city' => 'The city',
        ];
        $this->expectedResponse = [
            'name' => 'New item',
            'address' => null,
            'zipcode' => null,
            'city' => 'The city',
            'companies' => [],
            'lat' => null,
            'lng' => null,
        ];

        parent::testCreateResource();
    }
}
