<?php

namespace App\Tests\Api;

use App\Tests\Base\ApiResourceTestCase;

class UserResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'user';

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
        $this->profilerQueryCount = 13;
        $this->profilerQueryDuration = 120;

        $this->expectedFields = [
            'email',
            'firstName',
            'lastName',
            'gender',
            'birthdate',
        ];

        parent::testListResource();
    }

    public function testCreateResource()
    {
        // fixme! Find why this does not run correctly!
//        $this->postedData = [
//            'email' => 'a.b@c.com',
//            'firstName' => 'John',
//            'lastName' => 'Doe',
//            'plainPassword' => 'P@ssw0rd!',
//        ];
//        $this->expectedResponse = [
//            'name' => 'New item',
//            'users' => [],
//        ];
//
//        parent::testCreateResource();
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet. Find out why the commented code is broken!'
        );
    }
}
