<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\FrequencyRandomStrategy;
use PHPUnit\Framework\TestCase;

class FrequencyRandomStrategyTest extends TestCase
{
    public function testFrequencyRandom(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $strategy = new FrequencyRandomStrategy(frequency: 0.8, depth: 0.2);

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

        $this->assertEquals(1000, array_sum($indexes));
        $this->assertGreaterThan($indexes[1], $indexes[0]);
        $this->assertGreaterThan($indexes[2], $indexes[0]);
    }
}
