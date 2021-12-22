<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Strategy\GcdCalculator;
use PHPUnit\Framework\TestCase;

class GcdCalculatorTest extends TestCase
{
    public function testCalculate(): void
    {
        $gcd = GcdCalculator::calculate(12, 21);

        $this->assertEquals(3, $gcd);
    }
}
