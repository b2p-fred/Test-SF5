<?php

use App\Entity\Building;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        fwrite(STDOUT, __METHOD__."setUpBeforeClass\n");
    }

    protected function setUp(): void
    {
        fwrite(STDOUT, __METHOD__."setUp\n");
    }

    protected function tearDown(): void
    {
        fwrite(STDOUT, __METHOD__."tearDown\n");
    }

    public static function tearDownAfterClass(): void
    {
        fwrite(STDOUT, __METHOD__."tearDownAfterClass\n");
    }

    protected function onNotSuccessfulTest(\Throwable $t): void
    {
        fwrite(STDOUT, __METHOD__."onNotSuccessfulTest\n");
        throw $t;
    }

    public function testConstruct()
    {
        $building = new Building();
        $this->assertEquals('', $building->getName());
        $this->assertEquals('', $building->getAddress());
        $this->assertEquals('', $building->getCity());
        $this->assertEquals('', $building->getZipcode());
    }
}
