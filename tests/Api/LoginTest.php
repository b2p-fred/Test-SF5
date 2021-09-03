<?php

namespace App\Tests\Api;

use App\Tests\_Base\CustomApiTestCase;

class LoginTest extends CustomApiTestCase
{
    /**
     * Test login with badly formatted data.
     */
    public function testBadLogin()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
            ],
        ]);
        $this->assertResponseStatusCodeSame(400, 'Invalid JSON');
    }

    /**
     * Test a successful login as an admin user.
     */
    public function testAdminLoginSuccess()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'big.brother@the-world.com',
                'password' => 'I@mTh3B0ss!',
            ],
        ]);
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test a login with bad credentials - unknown email.
     */
    public function testAdminLoginFailEmail()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'fmohier@b2pweb.fr',
                'password' => 'I@mTh3B0ss!',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401, 'Invalid credentials');
    }

    /**
     * Test a login with bad credentials - bad password.
     */
    public function testAdminLoginFailPassword()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'big.brother@the-world.com',
                'password' => 'Bad password',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401, 'Invalid credentials');
    }

    /**
     * Test a successful login as a normal user.
     */
    public function testUserLoginSuccess()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'gaston.lagaffe@edition-dupuis.com',
                'password' => 'Gaston!',
            ],
        ]);
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test a login with bad credentials - unknown email.
     */
    public function testUserLoginFailEmail()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'gaston.lagaffe@edition-dupuis.de',
                'password' => 'Gaston!',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401, 'Invalid credentials');
    }

    /**
     * Test a login with bad credentials - bad password.
     */
    public function testUserLoginFailPassword()
    {
        static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'gaston.lagaffe@edition-dupuis.com',
                'password' => 'Bad password',
            ],
        ]);
        $this->assertResponseStatusCodeSame(401, 'Invalid credentials');
    }
}
