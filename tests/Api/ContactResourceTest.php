<?php

namespace App\Tests\Api;

use App\Tests\_Base\ApiResourceTestCase;

class ContactResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'contact';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, null, null, 10);
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
            'firstName',
            'lastName',
            'email',
            'language',
            'phone',
            'identifier',
            'password',
            'site',
            'createdAt',
            'updatedAt',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        self::$postedData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@the-world.com',
            'type' => 'simple',
            'identifier' => 'John',
            'password' => 'P@ssw0rd!',
            'phone' => '+337123456'
        ];
        $this->expectedResponse = [
            "@context" => "/api/contexts/Contact",
//            "@id" => "/api/contacts/abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "@type" => "Contact",
//            "id" => "abfae75d-8af7-4ff0-a3c8-2c8a82f9583d",
            "type" => "simple",
            "firstName" => "John",
            "lastName" => "Doe",
            "email" => "john.doe@the-world.com",
            "identifier" => "John",
            "password" => "P@ssw0rd!",
            "language" => "fr-FR",
            "phone" => "+337123456",
            "site" => null
        ];

        parent::testCreateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }

    public function testUpdateResource()
    {
        self::$postedData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@the-world.com',
            'type' => 'simple',
            'identifier' => 'john',
            'password' => 'P@ssw0rd!',
            'phone' => '+337123456'
        ];
        $this->updateData = [
            "type" => "simple",
            "firstName" => "Jane",
            "lastName" => "Doe",
            "email" => "jane.doe@the-world.com",
            "identifier" => "Jane",
        ];

        parent::testUpdateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }

    public function testDeleteResource()
    {
        self::$postedData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@the-world.com',
            'type' => 'simple',
            'identifier' => 'john',
            'password' => 'P@ssw0rd!',
            'phone' => '+337123456'
        ];

        parent::testDeleteResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
    }
}
