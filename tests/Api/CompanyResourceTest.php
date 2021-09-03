<?php

namespace App\Tests\Api;

use App\Tests\_Base\ApiResourceTestCase;

class CompanyResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'company';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, 'companies', null, 10);
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
        $this->profilerQueryDuration = 100;

        $this->expectedFields = [
            'name',
            'building',
            'users',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        self::$verbose = false;

        self::$postedData = [
            'name' => 'New item',
        ];
        $this->expectedResponse = [
            'name' => 'New item',
            'users' => [],
        ];

        parent::testCreateResource();
    }

}
