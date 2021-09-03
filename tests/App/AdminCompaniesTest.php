<?php

namespace App\Tests\App;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCompaniesTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['email' => 'big.brother@the-world.com']);
//        $testUser = $userRepository->findOneByEmail('big.brother@the-world.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testCompaniesPage(): void
    {
        // Request the companies page
        $crawler = $this->client->request('GET', '/admin/companies');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Hello companies!');
    }

    /**
     * @depends testCompaniesPage
     */
    public function testCompanyPage(): void
    {
        // Request the companies page
        $this->client->request('GET', '/admin/companies');

        // Click to get the company page
        $this->client->clickLink('Company ACME 1');
        $crawler = $this->client->getCrawler();
//        dump($this->client);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $this->assertCount(1, $crawler->filter('form[name="companyForm"]'));
        $this->assertCount(1, $crawler->filter('input[name="_name"]'));
        $this->assertCount(1, $crawler->filter('a[href^="/admin/building/"]'));

        $this->assertFormValue('form[name="companyForm"]', '_name', 'Company ACME 1');
    }

    public function testNewCompanyPage(): void
    {
        $this->client->request('GET', '/admin/company/create');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
    }
}
