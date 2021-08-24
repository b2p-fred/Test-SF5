<?php

namespace App\Tests\App;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminBuildingsTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['email' => 'big.brother@theworld.com']);
//        $testUser = $userRepository->findOneByEmail('big.brother@theworld.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testBuildingsPage(): void
    {
        // Request the buildings page
        $crawler = $this->client->request('GET', '/admin/buildings');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Hello buildings!');
    }

    /**
     * @depends testBuildingsPage
     */
    public function testBuildingPage(): void
    {
        // Request the buildings page
        $this->client->request('GET', '/admin/buildings');

        // Click to get the building page
        $this->client->clickLink('Building 1');
        $crawler = $this->client->getCrawler();

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $this->assertCount(1, $crawler->filter('form[name="buildingForm"]'));
        $this->assertCount(1, $crawler->filter('input[name="_name"]'));
        $this->assertCount(1, $crawler->filter('input[name="_address"]'));
        $this->assertCount(1, $crawler->filter('input[name="_zipcode"]'));
        $this->assertCount(1, $crawler->filter('input[name="_city"]'));

        $this->assertFormValue('form[name="buildingForm"]', '_name', 'Building 1');
        $this->assertFormValue('form[name="buildingForm"]', '_address', 'Rue des jardins');
        $this->assertFormValue('form[name="buildingForm"]', '_zipcode', '26500');
        $this->assertFormValue('form[name="buildingForm"]', '_city', 'Bourg-lès-Valence');
    }
}
