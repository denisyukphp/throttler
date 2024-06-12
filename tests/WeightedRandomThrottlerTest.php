<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests;

use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\WeightedRandomThrottler;
use PHPUnit\Framework\TestCase;

final class WeightedRandomThrottlerTest extends TestCase
{
    public function testWeightedRandomAlgorithm(): void
    {
        $throttler = new WeightedRandomThrottler();
        $collection = new InMemoryCollection([
            new Node('192.168.0.1', 10),
            new Node('192.168.0.2', 5),
            new Node('192.168.0.3', 1),
        ]);

        $actualNodes = [];

        for ($i = 0; $i < 1_000; ++$i) {
            $node = $throttler->pick($collection);

            if (!isset($actualNodes[$node->getName()])) {
                $actualNodes[$node->getName()] = 0;
            }

            ++$actualNodes[$node->getName()];
        }

        $this->assertCount(3, $actualNodes);
        $this->assertEquals(1_000, array_sum($actualNodes));
        $this->assertGreaterThan($actualNodes['192.168.0.2'], $actualNodes['192.168.0.1']);
        $this->assertGreaterThan($actualNodes['192.168.0.3'], $actualNodes['192.168.0.2']);
    }
}
