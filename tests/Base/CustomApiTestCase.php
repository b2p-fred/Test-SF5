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

    public const API_OPTIONS_DEFAULTS = [
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
        return static::createClient($kernelOptions, [
            'base_uri' => 'http://localhost:8000',
        ]);
    }

}
