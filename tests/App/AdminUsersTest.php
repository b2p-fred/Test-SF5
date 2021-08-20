<?php

namespace App\Tests\App;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminUsersTest extends WebTestCase
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

    /**
     * @depends testUsersPage
     */
    public function testUserPage(): void
    {
        // Request the users page
        $this->client->request('GET', '/admin/users');

        // Click to get the user page
        $this->client->clickLink('Fiche');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h2', 'Fiche utilisateur');
    }
}
