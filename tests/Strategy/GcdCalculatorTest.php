<?php

namespace Strategy;

use Orangesoft\Throttler\Strategy\GcdCalculator;
use PHPUnit\Framework\TestCase;

class GcdCalculatorTest extends TestCase
{
    public function testCalculate(): void
    {
        $gcd = GcdCalculator::calculate(12, 21);

        $this->assertSame(3, $gcd);
    }
}
