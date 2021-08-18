<?php

namespace Utils;

use PHPUnit\Framework\TestCase;
use Throwable;

class StackTest extends TestCase
{
    /*
     * Testing events: setup and tear-down
     */
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

    protected function onNotSuccessfulTest(Throwable $t): void
    {
        fwrite(STDOUT, __METHOD__."onNotSuccessfulTest\n");
        throw $t;
    }

    /*
     * Tests depending on tests:
     * empty <- push <- pop
     */
    public function testEmpty(): array
    {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @depends testEmpty
     */
    public function testPush(array $stack): array
    {
        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack) - 1]);
        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends testPush
     */
    public function testPop(array $stack)
    {
        $this->assertSame('foo', array_pop($stack));
        $this->assertEmpty($stack);
    }
}
