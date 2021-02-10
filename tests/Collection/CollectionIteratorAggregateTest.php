<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Collection;

class CollectionIteratorAggregateTest extends TestCase
{
    public function testIterable(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $this->assertIsIterable($collection);

        foreach ($collection as $node) {
            $this->assertInstanceOf(NodeInterface::class, $node);
        }
    }
}
