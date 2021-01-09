<?php

namespace Orangesoft\Throttler\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Strategy\GcdCalculator;

class GcdCalculatorTest extends TestCase
{
    public function testCalculate()
    {
        $gcd = GcdCalculator::calculate(12, 21);

        $this->assertSame(3, $gcd);
    }
}
