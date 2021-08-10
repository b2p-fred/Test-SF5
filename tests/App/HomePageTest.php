<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertPageTitleSame('Welcome!');
        $this->assertSelectorTextContains('h1', 'Your lucky number');

        // HTML contains a navigation bar with some anchors
        $this->assertSelectorExists('a[href="/"]');
        // Get an element text
//        echo $crawler->filter('a[href="/"]')->text();

        // A first anchor to /
        $this->assertSelectorTextContains('a[href="/"]', 'Nav');
        $this->assertSelectorTextSame('a[href="/"]', 'Navbar');

        $this->assertCount(2, $crawler->filter('a[href="/"]'));

        $this->assertCount(1, $crawler->filter('a[href="/login"]'));
        $this->assertSelectorTextSame('a[href="/login"]', 'Welcome');
    }
}
