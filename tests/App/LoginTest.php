<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();
    }

    public function testUserLoginAccepted(): void
    {
        // Request the login page
        $crawler = $this->client->request('GET', '/login');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Please sign in');

        // HTML contains a form with some input fields
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('input[type="email"]'));
        $this->assertCount(1, $crawler->filter('input[type="password"]'));
        $this->assertCount(1, $crawler->filter('input[type="hidden"]'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]'));

        // Signing in a user
        $crawler = $this->client->submitForm('Sign in', [
            'email' => 'fmohier@b2pweb.com',
            'password' => 'fmohier@b2pweb.com',
        ]);
        // Go to the home page
        $this->assertResponseRedirects('/');
    }

    public function testUserLoginFailed(): void
    {
        // Request the login page
        $crawler = $this->client->request('GET', '/login');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Please sign in');

        // HTML contains a form with some input fields
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('input[type="email"]'));
        $this->assertCount(1, $crawler->filter('input[type="password"]'));
        $this->assertCount(1, $crawler->filter('input[type="hidden"]'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]'));

        // Signing in a user
        $crawler = $this->client->submitForm('Sign in', [
            'email' => 'fmohier@b2pweb.com',
            'password' => 'A_bad_password',
        ]);
        // Go to the login page!
        $this->assertResponseRedirects('/login');
    }
}
