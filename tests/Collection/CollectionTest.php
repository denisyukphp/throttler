<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;

class CollectionTest extends TestCase
{
    public function testAdd()
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
        ];

        $collection = new Collection($nodes);

        $collection
            ->addNode(new Node('node3'))
            ->addNode(new Node('node4'))
        ;

        $this->assertInstanceOf(Collection::class, $collection);

        $this->assertNotEmpty($collection);

        foreach ($collection as $node) {
            $this->assertInstanceOf(Node::class, $node);
        }
    }

    public function testEmpty()
    {
        $collection = new Collection();

        $this->assertTrue($collection->isEmpty());
    }

    public function testNotEmpty()
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

    public function testQuantity()
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
