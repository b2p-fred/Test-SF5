<?php

namespace Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstruct()
    {
        $user = new User();
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('', $user->getFirstName());
        $this->assertEquals('', $user->getLastName());
        $this->assertEquals('', $user->getEmail());
        $this->assertEquals('', $user->getPassword());
        $this->assertEquals([], $user->getRoles());
    }

    public function testGettersSetters()
    {
        $user = new User();

        $user->setFirstName('A');
        $this->assertEquals('A', $user->getFirstName());
        $user->setLastName('A');
        $this->assertEquals('A', $user->getLastName());
        $user->setEmail('A');
        $this->assertEquals('A', $user->getEmail());
        $user->setPassword('A');
        $this->assertEquals('A', $user->getPassword());
        $user->setRoles(['A', 'B']);
        $this->assertEquals(['A', 'B'], $user->getRoles());
    }
}
