<?php

namespace App\Tests\Base;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CustomApiTestCase extends ApiTestCase
{
    // This trait provided by HautelookAliceBundle will take care of refreshing
    // the database content to a known state before each test
    use RefreshDatabaseTrait;

    protected Client $myTestClient;

    protected string $token;

    public array $defaultHeaders = [
        'accept' => ['application/ld+json'],
    ];

    public array $defaultOptions = [
        'auth_basic' => null,
        'auth_bearer' => null,
        'query' => [],
        'headers' => ['accept' => ['application/ld+json']],
        'body' => '',
        'json' => null,
        'base_uri' => 'http://localhost:8000',
        'extra' => [],
    ];

    /**
     * Create the test client with some specific options. The main reason is to allow
     * setting the API base URI used for all the tests.
     *
     * @param array $kernelOptions Provide the Kernel options if some are needed
     */
    protected static function createMyClient(array $kernelOptions = []): Client
    {
        return static::createClient($kernelOptions, (new CustomApiTestCase())->defaultOptions);
    }

    /**
     * Create a client with a default Authorization header.
     */
    protected function createAuthenticatedClient(string $username = 'big.brother@theworld.com', string $password = 'I@mTh3B0ss!'): Client
    {
        $client = static::createMyClient();
        $response = $this->logIn($client, $username, $password);

        // Set the default Bearer for the Authorization
        $this->defaultOptions['auth_bearer'] = $response['token'];
        $client->setDefaultOptions($this->defaultOptions);

        return $client;
    }

    /**
     * Log in a user with the provided client and credentials.
     */
    protected function logIn(Client $client, string $email, string $password)
    {
        $response = $client->request('POST', '/api/login_check', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        return \json_decode($response->getContent(), true);
    }
}
