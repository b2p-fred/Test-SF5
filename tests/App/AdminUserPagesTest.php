<?php

namespace App;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminUserPagesTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(['email' => 'fmohier@b2pweb.com']);
//        $testUser = $userRepository->findOneByEmail('fmohier@b2pweb.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testUsersPage(): void
    {
        // Request the users page
        $crawler = $this->client->request('GET', '/admin/users');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Liste des utilisateurs');
    }

    public function testUserPage(): void
    {
        // Request the user page
        $crawler = $this->client->request('GET', '/admin/user/1');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h2', 'Fiche utilisateur');
    }

    public function testBuildingsPage(): void
    {
        // Request the building page
        $crawler = $this->client->request('GET', '/admin/buildings');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Hello buildings!');
    }

    public function testBuildingPage(): void
    {
        // Request the building page
        $crawler = $this->client->request('GET', '/admin/building/1');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $this->assertCount(1, $crawler->filter('form[name="buildingForm"]'));
        $this->assertCount(1, $crawler->filter('input[name="_name"]'));
        $this->assertCount(1, $crawler->filter('input[name="_address"]'));
        $this->assertCount(1, $crawler->filter('input[name="_zipcode"]'));
        $this->assertCount(1, $crawler->filter('input[name="_city"]'));

        $this->assertFormValue('form[name="buildingForm"]', '_name', 'Building 1');
        $this->assertFormValue('form[name="buildingForm"]', '_address', 'Rue des fleurs');
        $this->assertFormValue('form[name="buildingForm"]', '_zipcode', '26500');
        $this->assertFormValue('form[name="buildingForm"]', '_city', 'Bourg-lès-Valence');
    }

    public function testCompaniesPage(): void
    {
        // Request the building page
        $crawler = $this->client->request('GET', '/admin/companies');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Hello companies!');
    }

    public function testCompanyPage(): void
    {
        // Request the building page
        $crawler = $this->client->request('GET', '/admin/company/1');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $this->assertCount(1, $crawler->filter('form[name="companyForm"]'));
        $this->assertCount(1, $crawler->filter('input[name="_name"]'));
        $this->assertCount(1, $crawler->filter('a[href="/admin/building/1"]'));

        $this->assertFormValue('form[name="companyForm"]', '_name', 'Acme 1');
    }
}
