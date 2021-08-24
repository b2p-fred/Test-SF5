<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class UserTest extends TestCase
{
    public function testConstruct()
    {
        $user = new User();
        $this->assertInstanceOf(UuidV4::class, $user->getId());
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
