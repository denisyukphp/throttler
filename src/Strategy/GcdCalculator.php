<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

final class GcdCalculator
{
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
