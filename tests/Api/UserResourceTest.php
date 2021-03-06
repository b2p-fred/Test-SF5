<?php

namespace App\Tests\Api;

use App\Tests\_Base\ApiResourceTestCase;

class UserResourceTest extends ApiResourceTestCase
{
    public const RESOURCE = 'user';

    protected function setUp(): void
    {
        parent::configure(self::RESOURCE, null, null, null, 1000);
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
        $this->profilerQueryDuration = 200;

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
        /*
         * fixme! Find why this does not run correctly!
         * API response:
         *  {
         *      "@context":"\/api\/contexts\/ConstraintViolationList",
         *      "@type":"ConstraintViolationList",
         *      "hydra:title":"An error occurred",
         *      "hydra:description":"plainPassword: This value should not be blank.",
         *      "violations":[{
         *          "propertyPath":"plainPassword",
         *          "message":"This value should not be blank.",
         *          "code":"c1051bb4-d103-4f74-8988-acbcafc7fdc3"
         *      }]
         *  }
         */
//        self::$postedData = [
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
