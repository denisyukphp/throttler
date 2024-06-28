<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests;

use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\RandomThrottler;
use PHPUnit\Framework\TestCase;

final class RandomThrottlerTest extends TestCase
{
    public function testRandomAlgorithm(): void
    {
        $throttler = new RandomThrottler();
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
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
    }
}
