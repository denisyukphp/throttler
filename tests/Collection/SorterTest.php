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
    public function testSortAsc(): void
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

        $this->assertSame('node1', $sortedCollection->getNode(0)->getName());
        $this->assertSame('node2', $sortedCollection->getNode(1)->getName());
        $this->assertSame('node3', $sortedCollection->getNode(2)->getName());
        $this->assertSame('node4', $sortedCollection->getNode(3)->getName());
    }

    public function testSortDesc(): void
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

        $this->assertSame('node4', $sortedCollection->getNode(0)->getName());
        $this->assertSame('node3', $sortedCollection->getNode(1)->getName());
        $this->assertSame('node2', $sortedCollection->getNode(2)->getName());
        $this->assertSame('node1', $sortedCollection->getNode(3)->getName());
    }
}
