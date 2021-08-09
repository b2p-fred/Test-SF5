<?php

namespace App\Utils;

use InvalidArgumentException;

class Math
{
    public function fibonacci($n): int
    {
        if (is_int($n) && $n > 0) {
            $elements = [];
            $elements[1] = 1;
            $elements[2] = 1;
            for ($i = 3; $i <= $n; ++$i) {
                $elements[$i] = \bcadd($elements[$i - 1], $elements[$i - 2]);
            }

            return $elements[$n];
        } else {
            throw new InvalidArgumentException('You should pass integer greater than 0');
        }
    }

    public function factorial($n): int
    {
        if (is_int($n) && $n >= 0) {
            $factorial = 1;
            for ($i = 2; $i <= $n; ++$i) {
                $factorial *= $i;
            }

            return $factorial;
        } else {
            throw new InvalidArgumentException('You should pass non-negative integer');
        }
    }
}
