<?php

namespace Orangesoft\Throttler\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\FrequencyRandomStrategy;

class FrequencyRandomStrategyTest extends TestCase
{
    public function testFrequencyRandom()
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $strategy = new FrequencyRandomStrategy(80, 20);

        $indexes = [];

        for ($i = 0; $i < 1000; $i++) {
            $index = $strategy->getIndex($collection);

            if (!isset($indexes[$index])) {
                $indexes[$index] = 0;
            }

            $indexes[$index]++;
        }

        $this->assertCount(3, $indexes);

        foreach ($indexes as $count) {
            $this->assertGreaterThan(0, $count);
        }

        $this->assertSame(1000, array_sum($indexes));
        $this->assertGreaterThan($indexes[1], $indexes[0]);
        $this->assertGreaterThan($indexes[2], $indexes[0]);
    }
}
