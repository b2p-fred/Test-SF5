<?php

namespace Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstruct()
    {
        $user = new User();
//        $this->assertEquals(null, $user->getId());
//        $this->assertEquals('', $user->getFirstName());
//        $this->assertEquals('', $user->getLastName());
//        $this->assertEquals('', $user->getEmail());
//        $this->assertEquals('', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
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
        $this->assertEquals('A', $user->getUserIdentifier());
        $user->setPassword('A');
        $this->assertEquals('A', $user->getPassword());
        $user->setRoles(['A', 'B']);
        $this->assertEquals(['A', 'B', 'ROLE_USER'], $user->getRoles());
        $user->setIsVerified(true);
        $this->assertEquals(true, $user->isVerified());
    }
}
