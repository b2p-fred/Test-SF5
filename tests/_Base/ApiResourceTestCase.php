<?php

namespace App\Tests\_Base;

/**
 * Base class for testing all the API endpoints.
 *
 * Inheriting this class allows using easily some base services common to all the
 * API resources.
 *
 * 1. call the configure function to setup the test fixture
 * 2. call the testDenied function to run the for checking access denial without authentication
 * 3. call the testListResource with the expected fields to test the collections GET and control
 * the received information
 * 4. call the
 */
class ApiResourceTestCase extends CustomApiTestCase
{
    public const API_PREFIX = '/api';

    public static bool $verbose = false;

    private static string $resource;
    private static string $resources;

    private static string $type = '';
    private static string $types = 'hydra:Collection';

    private static int $count = -1;
    private static int $pageSize = 10;

    private static int $itemsCount = -1;
    private static int $totalItemsCount = -1;
    private static int $pageCount = -1;

    public bool $profilerEnabled = false;
    public int $profilerQueryCount = 10;
    public int $profilerQueryDuration = 100;

    // In the collection, check all those fields are present
    public array $expectedFields = [];

    // Post some data for a new resource creation
    public static array $postedData = [];
    public array $expectedResponse = [];

    // Data to update a resource
    public array $updateData = [];

    /**
     * Configure the test class.
     *
     * @param string      $resource  The base resource name (e.g. building)
     * @param string|null $type      The type of the resource.
     *                               If null it will be built from the resource (e.g. Building)
     * @param string|null $resources The plural form of the resource.
     *                               If null it will be built from the resource (e.g. buildings)
     * @param string|null $types     The type for the resource collection (default: hydra:Collection)
     * @param int|null    $count     The resource count in the test fixtures (default: -1)
     * @param int|null    $pageSize  The configured page size (default: 10)
     * @param int|null    $pageCount The page count (default: 10)
     */
    public static function configure(string $resource, string $type = null, string $resources = null, string $types = null, int $count = null, int $pageSize = null, int $pageCount = null)
    {
        self::$resource = self::API_PREFIX.'/'.$resource;

        self::$type = ucfirst($resource);
        if ($type) {
            self::$type = $type;
        }

        self::$resources = self::$resource.'s';
        if ($resources) {
            self::$resources = self::API_PREFIX.'/'.$resources;
        }

        if ($types) {
            self::$types = $types;
        }

        if ($count) {
            self::$count = $count;
        }
        if ($pageSize) {
            self::$pageSize = $pageSize;
        }
        if ($pageCount) {
            self::$pageCount = $pageCount;
        } else {
            if (-1 !== self::$count) {
                self::$pageCount = intdiv(self::$count - 1, self::$pageSize) + 1;
            }
        }
    }

    /**
     * Confirm the resource access is denied when not authenticated.
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testDenied()
    {
        static::createClient()->request('GET', self::$resources);
        $this->assertResponseStatusCodeSame(401, 'JWT Token not found');

        static::createClient()->request('GET', self::$resources);
        $this->assertResponseStatusCodeSame(401, 'JWT Token not found');

        static::createClient()->request('POST', self::$resources, [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401, 'JWT Token not found');
    }

    /**
     * Test the listing of a resources' collection.
     * -----
     * How-to:
     * In an inherited class:
     * - define the fields that are expected for each item of the collection
     *  $this->expectedFields = [
     *      'firstName',
     *      'lastName',
     *  ].
     *
     * - optionally activate the Symfony profiler and specify the maximum
     * query count and duration
     *  $this->profilerEnabled = true;
     *  $this->profilerQueryCount = 13;
     *  $this->profilerQueryDuration = 120;
     */
    public function testListResource()
    {
        if (!empty($expectedFields)) {
            $this->expectedFields = $expectedFields;
        }

        $client = static::createAuthenticatedClient();

        // ### Profiler
        // enable the profiler only for the next request (if you make
        // new requests, you must call this method again)
        // (it does nothing if the profiler is not available)
        if ($this->profilerEnabled) {
            $client->enableProfiler();
        }

        // Get the default items list
        try {
            $response = $client->request('GET', self::$resources);
        } catch (\Exception $exception) {
            $this->assertFalse('Got an exception: '.$exception->getMessage());

            return;
        }
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received some data: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----';
        }

        // Some checks in the data
        $this->assertEquals('/api/contexts/'.self::$type, $json['@context']);
        $this->assertEquals(self::$resources, $json['@id']);
        $this->assertEquals(self::$types, $json['@type']);

        // Total items count
        $this->assertArrayHasKey('hydra:totalItems', $json);
        if (-1 !== self::$count) {
            $this->assertEquals(self::$count, $json['hydra:totalItems']);
        } else {
            if (self::$verbose) {
                echo PHP_EOL.'***** '.__METHOD__.', did not checked total items count.'.' *****'.PHP_EOL;
            }
        }
        self::$totalItemsCount = (int) $json['hydra:totalItems'];

        $this->assertArrayHasKey('hydra:member', $json);
        $this->assertArrayHasKey('@id', $json['hydra:member'][0]);
        $this->assertArrayHasKey('@type', $json['hydra:member'][0]);
        self::$itemsCount = count($json['hydra:member']);

        // Check expected fields are present
        $receivedFieldsList = array_keys($json['hydra:member'][0]);
        foreach ($this->expectedFields as $field) {
            $this->assertArrayHasKey(
                $field,
                $json['hydra:member'][0],
                sprintf("Expected to receive a field named '%s' but it is not present in: %s", $field, implode(',', $receivedFieldsList)));
        }

        self::$pageSize = count($json['hydra:member']);
        if (isset($json['hydra:view'])) {
            $this->assertArrayHasKey('hydra:view', $json);
            $this->assertEquals(self::$resources.'?page=1', $json['hydra:view']['@id']);
            $this->assertEquals('hydra:PartialCollectionView', $json['hydra:view']['@type']);
            $this->assertEquals(self::$resources.'?page=1', $json['hydra:view']['hydra:first']);
            if (-1 !== self::$count) {
                $this->assertEquals(self::$resources.'?page='.self::$pageCount, $json['hydra:view']['hydra:last']);
            }
            $this->assertEquals(self::$resources.'?page=2', $json['hydra:view']['hydra:next']);
        } else {
            self::$totalItemsCount = count($json['hydra:member']);

            // Only one page for all the items
            if (self::$verbose) {
                echo PHP_EOL.'----- '.__METHOD__.', no pagination.'.' -----'.PHP_EOL;
            }
        }
        self::$pageCount = intdiv(self::$totalItemsCount - 1, self::$pageSize) + 1;
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.sprintf(', received %d out of %d %s, %d pages', self::$itemsCount, self::$totalItemsCount, self::$type, self::$pageCount).' -----'.PHP_EOL;
        }

        // ### Profiler
        // check that the profiler is enabled
        if ($this->profilerEnabled && $profile = $client->getProfile()) {
            // @phpstan-ignore-next-line
            $queryCount = $profile->getCollector('db')->getQueryCount();
            // @phpstan-ignore-next-line
            $queryDuration = $profile->getCollector('time')->getDuration();
            if (self::$verbose) {
                echo PHP_EOL.'----- '.__METHOD__.sprintf(', profiler query count: %d queries, duration: %d ms.', $queryCount, $queryDuration).' -----'.PHP_EOL;
            }

            // check the number of requests
            $this->assertLessThanOrEqual(
                $this->profilerQueryCount, $queryCount,
                sprintf('Checks that query count is less than %d (token %s)', $this->profilerQueryCount, $profile->getToken())
            );

            // check the time spent in the framework
            $this->assertLessThanOrEqual(
                $this->profilerQueryDuration, $queryDuration,
                sprintf('Checks that time is less than %d (token %s)', $this->profilerQueryDuration, $profile->getToken())
            );
        }
    }

    /**
     * Test the creation of a new resource.
     * -----
     * How-to:
     * In an inherited class:
     * - define the data to post for creating a new resource
     *  self::$postedData = [
     *      'firstName' => 'John',
     *      'lastName' => 'Doe',
     *  ]
     * - define the expected result:
     *  $this->expectedResponse = [
     *      "type" => "simple",
     *      "firstName" => "John",
     *      "lastName" => "Doe",
     *  ]
     * - call this method:
     *  parent::testCreateResource();.
     *
     * @depends testListResource
     */
    public function testCreateResource()
    {
        $client = static::createAuthenticatedClient();

        // 1- Add one more item in the list
        try {
            $response = $client->request('POST', self::$resources, [
                    'json' => self::$postedData,
                ]
            );
        } catch (\Exception $exception) {
            $this->assertFalse('Got an exception: '.$exception->getMessage());

            return;
        }
        $this->assertResponseStatusCodeSame(201);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received a creation response: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----'.PHP_EOL;
        }

        // 2- Some checks in the response data
        $this->assertEquals('/api/contexts/'.self::$type, $json['@context']);
        $this->assertStringStartsWith(self::$resources, $json['@id']);
        $this->assertEquals(self::$type, $json['@type']);

        // Check expected fields are present with correct values
        $receivedItem = $json;
        $receivedFieldsList = array_keys($json);
        foreach ($this->expectedResponse as $field => $value) {
            $this->assertArrayHasKey(
                $field,
                $json,
                sprintf("Expected to receive a field named '%s' but it is not present in: %s", $field, implode(',', $receivedFieldsList))
            );
            $this->assertEquals($value, $json[$field]);
        }

        // 3- Item get the new resource
        $response = $client->request('GET', $json['@id']);
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received a get item response: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----'.PHP_EOL;
        }
        // Same as the created item!
        $this->assertEquals($receivedItem, $json);

        // 4- Confirm one more item in the list
        $response = $client->request('GET', self::$resources);
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received collection data: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----'.PHP_EOL;
        }

        // Total items count
        if (-1 !== self::$count) {
            $this->assertEquals(self::$totalItemsCount + 1, $json['hydra:totalItems']);
            self::$totalItemsCount = $json['hydra:totalItems'];
        } else {
            if (self::$verbose) {
                echo PHP_EOL.'***** '.__METHOD__.', did not checked total items count.'.' *****'.PHP_EOL;
            }
        }
    }

    /**
     * Test the update of a resource.
     * -----
     * How-to:
     * In an inherited class:
     * - it will reuse the former defined $postedData, but you can redefine them
     * - define the data to update:
     *  $this->updateData = [
     *      "type" => "simple",
     *      "firstName" => "Jane",
     *      "lastName" => "Doe",
     *  ]
     * - call this method:
     *  parent::testUpdateResource();.
     *
     * @depends testCreateResource
     */
    public function testUpdateResource()
    {
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', posted: '.serialize(self::$postedData).' -----'.PHP_EOL;
        }
        $client = static::createAuthenticatedClient();

        // 1 - Add one more item in the list
        $response = $client->request('POST', self::$resources, ['json' => self::$postedData]);
        $this->assertResponseStatusCodeSame(201);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', created a new resource: '.$json['@id'].' -----'.PHP_EOL;
        }
        $newExpectedData = array_merge($json, $this->updateData);

        // 2 - Update the data
        // PUT remplace et PATCH modifie, mais API Platform traite de la même façon
        // Si une donnée n'est pas envoyée en PUT elle ne sera pas supprimée
//        // PATCH impose d'utiliser le MIME type: "application/merge-patch+json"
//        $response = $client->request('PATCH', $json['@id'], [
//                'json' => $this->updateData,
//            ]
//        );
        $response = $client->request('PUT', $json['@id'], [
                'json' => $this->updateData,
            ]
        );
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received a patch item response: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----'.PHP_EOL;
        }

        // 3- Item get the new resource
        $response = $client->request('GET', $json['@id']);
        $this->assertResponseStatusCodeSame(200);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received a get item response: '.PHP_EOL;
            echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
            echo PHP_EOL.'-----'.PHP_EOL;
        }
        // Same as the expected data!
        $this->assertEquals($newExpectedData, $json);
    }

    /**
     * Test the deletion of a resource.
     * -----
     * How-to:
     * In an inherited class:
     * - it will reuse the former defined $postedData, but you can redefine them
     * - call this method:
     *  parent::testDeleteResource();.
     *
     * @depends testCreateResource
     */
    public function testDeleteResource()
    {
        $client = static::createAuthenticatedClient();

        // 1 - Add one more item in the list
        $response = $client->request('POST', self::$resources, ['json' => self::$postedData]);
        $this->assertResponseStatusCodeSame(201);
        $json = \json_decode($response->getContent(), true);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', created a new resource: '.$json['@id'].' -----'.PHP_EOL;
        }

        // 3 - Delete the new item
        $response = $client->request('DELETE', $json['@id']);
        $this->assertResponseStatusCodeSame(204);
        if (self::$verbose) {
            echo PHP_EOL.'----- '.__METHOD__.', received a delete item confirmation '.' -----'.PHP_EOL;
        }

        // 3- Item get the former resource -> does not exist anymore!
        $response = $client->request('GET', $json['@id']);
        $this->assertResponseStatusCodeSame(404);
    }
}
