<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Sorter;
use Orangesoft\Throttler\Collection\Asc;
use Orangesoft\Throttler\Collection\Desc;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    public function testSortAsc(): void
    {
        $collection = new Collection([
            new Node('node2', 8),
            new Node('node3', 16),
            new Node('node1', 4),
            new Node('node4', 32),
        ]);

        $sorter = new Sorter();

        $sorter->sort($collection, new Asc());

        $this->assertSame('node1', $collection->getNode(0)->name);
        $this->assertSame('node2', $collection->getNode(1)->name);
        $this->assertSame('node3', $collection->getNode(2)->name);
        $this->assertSame('node4', $collection->getNode(3)->name);
    }

    public function testSortDesc(): void
    {
        $collection = new Collection([
            new Node('node2', 8),
            new Node('node3', 16),
            new Node('node1', 4),
            new Node('node4', 32),
        ]);

        $sorter = new Sorter();

        $sorter->sort($collection, new Desc());

        $this->assertSame('node4', $collection->getNode(0)->name);
        $this->assertSame('node3', $collection->getNode(1)->name);
        $this->assertSame('node2', $collection->getNode(2)->name);
        $this->assertSame('node1', $collection->getNode(3)->name);
    }
}
