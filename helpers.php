<?php

declare(strict_types=1);

if (!function_exists('gcd')) {
    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    function gcd(int $a, int $b): int
    {
        while (0 != $b) {
            $c = $a % $b;
            $a = $b;
            $b = $c;
        }

        return $a;
    }
}
