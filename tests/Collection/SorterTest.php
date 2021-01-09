<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Sorter;
use Orangesoft\Throttler\Collection\Asc;
use Orangesoft\Throttler\Collection\Desc;

class SorterTest extends TestCase
{
    public function testSortAsc()
    {
        $nodes = [
            new Node('node2', 8),
            new Node('node3', 16),
            new Node('node1', 4),
            new Node('node4', 32),
        ];

        $collection = new Collection($nodes);

        $sorter = new Sorter();

        $sortedCollection = $sorter->sort($collection, new Asc());

        $this->assertInstanceOf(Collection::class, $sortedCollection);

        $minWeight = 4;

        foreach ($sortedCollection as $node) {
            $weight = $node->getWeight();

            $this->assertGreaterThanOrEqual($minWeight, $weight);

            $minWeight = $weight;
        }
    }

    public function testSortDesc()
    {
        $nodes = [
            new Node('node2', 8),
            new Node('node3', 16),
            new Node('node1', 4),
            new Node('node4', 32),
        ];

        $collection = new Collection($nodes);

        $sorter = new Sorter();

        $sortedCollection = $sorter->sort($collection, new Desc());

        $this->assertInstanceOf(Collection::class, $sortedCollection);

        $maxWeight = 32;

        foreach ($sortedCollection as $node) {
            $weight = $node->getWeight();

            $this->assertLessThanOrEqual($maxWeight, $weight);

            $maxWeight = $weight;
        }
    }
}
