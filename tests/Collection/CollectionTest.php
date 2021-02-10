<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;

class CollectionTest extends TestCase
{
    public function testGetNode(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ]);

        $node = $collection->getNode(3);

        $this->assertSame('node4', $node->getName());
    }

    public function testAddNode(): void
    {
        $collection = new Collection();

        $collection->addNode(new Node('node1'));
        $collection->addNode(new Node('node2'));
        $collection->addNode(new Node('node3'));
        $collection->addNode(new Node('node4'));

        $this->assertSame(4, $collection->getQuantity());
    }

    public function testHasNode(): void
    {
        $node = new Node('node1');

        $collection = new Collection([
            $node,
        ]);

        $this->assertTrue($collection->hasNode($node));
    }

    public function testRemoveNode(): void
    {
        $node = new Node('node1');

        $collection = new Collection([
            $node,
        ]);

        $collection->removeNode($node);

        $this->assertFalse($collection->hasNode($node));
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

    public function testToArray(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $this->assertIsArray($collection->toArray());
        $this->assertCount(4, $collection->toArray());
    }
}
