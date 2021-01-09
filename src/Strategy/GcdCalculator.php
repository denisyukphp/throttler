<?php

namespace Orangesoft\Throttler\Strategy;

class GcdCalculator
{
    /**
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    public static function calculate(int $a, int $b): int
    {
        while (0 !== $b) {
            $m = $a % $b;
            $a = $b;
            $b = $m;
        }

        return $a;
    }
}
