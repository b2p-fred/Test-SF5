<?php

namespace App\Tests\Api;

use App\Tests\Base\CustomApiTestCase;

class BuildingResourceTest extends CustomApiTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::createMyClient();
    }

    public function testAdminLogin()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'fmohier@b2pweb.com',
                'password' => 'Fred!',
            ],
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testDeniedCreateBuilding()
    {
        static::createClient()->request('POST', '/api/buildings', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @depends testAdminLogin
     */
    public function testAllowedCreateBuilding()
    {
        static::createClient()->request('POST', '/api/buildings', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);
    }
}
