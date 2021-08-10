<?php

namespace Utils;

use App\Utils\Math;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class MathTest extends TestCase
{
    public function testFibonacci()
    {
        $math = new Math();
        $this->assertEquals(34, $math->fibonacci(9));
    }

    public function testFactorial()
    {
        $math = new Math();
        $this->assertEquals(120, $math->factorial(5));
    }

    public function testFactorialGreaterThanFibonacci()
    {
        $math = new Math();
        $this->assertTrue($math->factorial(6) > $math->fibonacci(6));
    }

    public function providerFibonacci(): array
    {
        return [
            [1, 1],
            [2, 1],
            [3, 2],
            [4, 3],
            [5, 5],
            [6, 8],
        ];
    }

    /**
     * test with data from dataProvider.
     *
     * @dataProvider providerFibonacci
     */
    public function testFibonacciWithDataProvider($n, $result)
    {
        $math = new Math();
        $this->assertEquals($result, $math->fibonacci($n));
    }

    public function testExceptionsForNegativeNumbers()
    {
        $this->expectException(InvalidArgumentException::class);
        $math = new Math();
        $math->fibonacci(-1);
    }

    public function testFailedForZero()
    {
        $this->expectException(InvalidArgumentException::class);
        $math = new Math();
        $math->factorial(-1);
    }
}
