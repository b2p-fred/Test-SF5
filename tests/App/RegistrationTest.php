<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RegistrationTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();

    }

    public function testRegisterNewUser(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', '/register');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');

        // HTML contains a form with some input fields
        $this->assertCount(1, $crawler->filter('form[name="registration_form"]'));
        $this->assertCount(1, $crawler->filter('input[type="password"]'));
        $this->assertCount(1, $crawler->filter('input[type="email"]'));
        $this->assertCount(2, $crawler->filter('input[type="text"]'));
        $this->assertCount(1, $crawler->filter('input[type="checkbox"]'));
        $this->assertCount(1, $crawler->filter('input[type="hidden"]'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]'));

        // Registering a new user
        $crawler = $this->client->submitForm('Register', [
            'registration_form[firstName]' => 'Jean',
            'registration_form[lastName]' => 'Martin',
            'registration_form[email]' => 'jean.martin@gmail.com',
            'registration_form[plainPassword]' => 'Jeannot !',
            'registration_form[agreeTerms]' => '1',
        ]);
        $this->assertResponseRedirects('/');
    }

    /*
     * @depends testRegisterNewUser
     */
    public function testRegisterExistingUser(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', '/register');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');

        // HTML contains a form with some input fields
        $this->assertCount(1, $crawler->filter('form[name="registration_form"]'));
        $this->assertCount(1, $crawler->filter('input[type="password"]'));
        $this->assertCount(1, $crawler->filter('input[type="email"]'));
        $this->assertCount(2, $crawler->filter('input[type="text"]'));
        $this->assertCount(1, $crawler->filter('input[type="checkbox"]'));
        $this->assertCount(1, $crawler->filter('input[type="hidden"]'));
        $this->assertCount(1, $crawler->filter('button[type="submit"]'));

        // Registering the same user as in the former test
        $crawler = $this->client->submitForm('Register', [
            'registration_form[firstName]' => 'Jean',
            'registration_form[lastName]' => 'Martin',
            'registration_form[email]' => 'jean.martin@gmail.com',
            'registration_form[plainPassword]' => 'Jeannot !',
            'registration_form[agreeTerms]' => '1',
        ]);
        $this->assertResponseIsSuccessful();

        // HTML contains the filled form with an error for the email field
        $this->assertCount(1, $crawler->filter('form[name="registration_form"]'));
        $this->assertFormValue('form[name="registration_form"]', 'registration_form[firstName]', 'Jean');
        $this->assertFormValue('form[name="registration_form"]', 'registration_form[lastName]', 'Martin');
        $this->assertFormValue('form[name="registration_form"]', 'registration_form[email]', 'jean.martin@gmail.com');
        $this->assertFormValue('form[name="registration_form"]', 'registration_form[plainPassword]', '');

        $this->assertCount(1, $crawler->filter('label[for="registration_form_email"]>span>span>span.badge-danger'));
    }
}
