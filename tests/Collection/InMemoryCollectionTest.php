<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Sort\Asc;
use Orangesoft\Throttler\Collection\Sort\Desc;
use PHPUnit\Framework\TestCase;

final class InMemoryCollectionTest extends TestCase
{
    public function testCreateCollectionWithUniqueNodes(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $expectedResult = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];

        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $collection->toArray()));
    }

    public function testCreateCollectionWithNotUniqueNodes(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('All nodes must be unique, "192.168.0.1" given as duplicate.');

        new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.1'),
        ]);
    }

    public function testAddNodeToExistedCollection(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
        ]);

        $other = $collection->add(new Node('192.168.0.3'));

        $expectedResult = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];

        $this->assertNotSame($collection, $other);
        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $other->toArray()));
    }

    public function testAddTheSameNodeToExistedCollection(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
        ]);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The node "192.168.0.2" has been already added.');

        $collection->add(new Node('192.168.0.2'));
    }

    public function testGetNodeByKey(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $expectedResult = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];
        $actualResult = [];

        for ($i = 0; $i < 3; ++$i) {
            $actualResult[] = $collection->get($i);
        }

        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $actualResult));
    }

    public function testGetOutOfRangeNodeByKey(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('Can\'t get node at key "3".');

        $collection->get(3);
    }

    public function testHasNodeInCollection(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $this->assertTrue($collection->has(new Node('192.168.0.1')));
    }

    public function testHasNodeInEmptyCollection(): void
    {
        $collection = new InMemoryCollection();

        $this->assertFalse($collection->has(new Node('192.168.0.1')));
    }

    public function testRemoveNode(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $other = $collection->remove(new Node('192.168.0.3'));

        $expectedResult = [
            '192.168.0.1',
            '192.168.0.2',
        ];

        $this->assertNotSame($collection, $other);
        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $other->toArray()));
    }

    public function testRemoveNotAddedNode(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
        ]);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The node "192.168.0.3" hasn\'t been already added.');

        $collection->remove(new Node('192.168.0.3'));
    }

    public function testSortCollectionByWeightAscending(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1', 3),
            new Node('192.168.0.2', 2),
            new Node('192.168.0.3', 1),
        ]);

        $other = $collection->sort(new Asc());

        $expectedResult = [
            '192.168.0.3',
            '192.168.0.2',
            '192.168.0.1',
        ];

        $this->assertNotSame($collection, $other);
        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $other->toArray()));
    }

    public function testSortCollectionByWeightDescending(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1', 1),
            new Node('192.168.0.2', 2),
            new Node('192.168.0.3', 3),
        ]);

        $other = $collection->sort(new Desc());

        $expectedResult = [
            '192.168.0.3',
            '192.168.0.2',
            '192.168.0.1',
        ];

        $this->assertNotSame($collection, $other);
        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $other->toArray()));
    }

    public function testCollectionTraversable(): void
    {
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $expectedResult = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];
        $actualNodes = [];

        foreach ($collection as $key => $node) {
            $actualNodes[$key] = $node;
        }

        $this->assertSame($expectedResult, array_map(static fn (NodeInterface $node): string => $node->getName(), $actualNodes));
    }
}
