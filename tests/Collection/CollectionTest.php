<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testAddNode(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ]);

        $this->assertCount(4, $collection);
    }

    public function testGetNode(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $this->assertSame($nodes[2], $collection->getNode(2));
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

    public function testReindex(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $collection->removeNode($nodes[0]);

        $this->assertSame($nodes[1], $collection->getNode(0));
    }

    public function testPurge(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $collection->purge();

        $this->assertCount(0, $collection);
    }

    public function testWeightedCollection(): void
    {
        $collection = new Collection([
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ]);

        $this->assertTrue($collection->isWeighted());
    }

    public function testUnweightedCollection(): void
    {
        $collection = new Collection([
            new Node('node1', 5),
            new Node('node2', 0),
            new Node('node3', 0),
        ]);

        $this->assertFalse($collection->isWeighted());
    }

    public function testEmpty(): void
    {
        $collection = new Collection();

        $this->assertTrue($collection->isEmpty());
    }

    public function testNotEmpty(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->assertFalse($collection->isEmpty());
    }

    public function testToArray(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->assertIsArray($collection->toArray());
        $this->assertCount(3, $collection->toArray());
    }

    public function testCountable(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->assertCount(3, $collection);
    }

    public function testIterable(): void
    {
        $expectedNodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($expectedNodes);

        $this->assertIsIterable($collection);

        $actualNodes = iterator_to_array($collection);

        $this->assertSame($expectedNodes[0], $actualNodes[0]);
        $this->assertSame($expectedNodes[1], $actualNodes[1]);
        $this->assertSame($expectedNodes[2], $actualNodes[2]);
    }
}
