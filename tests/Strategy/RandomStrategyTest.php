<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use PHPUnit\Framework\TestCase;

class RandomStrategyTest extends TestCase
{
    public function testRandom(): void
    {
        $nodes = [
            'node1' => new Node('node1'),
            'node2' => new Node('node2'),
            'node3' => new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $strategy = new RandomStrategy();

        $indexes = [];

        for ($i = 0; $i < 1000; $i++) {
            $node = $strategy->getNode($collection);

            if (!isset($indexes[$node->name])) {
                $indexes[$node->name] = 0;
            }

            $indexes[$node->name]++;
        }

        $this->assertCount(3, $indexes);

        foreach ($indexes as $count) {
            $this->assertGreaterThan(0, $count);
        }

        $this->assertEquals(1000, array_sum($indexes));
    }
}
