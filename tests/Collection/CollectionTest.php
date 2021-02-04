<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;

class CollectionTest extends TestCase
{
    public function testAdd(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $this->assertInstanceOf(Collection::class, $collection);

        $this->assertNotEmpty($collection);

        foreach ($collection as $node) {
            $this->assertInstanceOf(Node::class, $node);
        }
    }

    public function testEmpty(): void
    {
        $collection = new Collection();

        $this->assertTrue($collection->isEmpty());
    }

    public function testNotEmpty(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $this->assertFalse($collection->isEmpty());
    }

    public function testQuantity(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $this->assertSame(4, $collection->getQuantity());
    }
}
