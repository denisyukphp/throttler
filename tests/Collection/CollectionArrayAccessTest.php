<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;

class CollectionArrayAccessTest extends TestCase
{
    public function testExists(): void
    {
        $collection = new Collection([
            new Node('node1'),
        ]);

        $this->assertTrue(isset($collection[0]));
    }

    public function testNotExists(): void
    {
        $collection = new Collection();

        $this->assertFalse(isset($collection[0]));
    }

    public function testGet(): void
    {
        $collection = new Collection([
            new Node('node1'),
        ]);

        $node = $collection[0];

        $this->assertSame('node1', $node->getName());
    }

    public function testGetNotExists(): void
    {
        $collection = new Collection();

        $this->expectException(\OutOfRangeException::class);

        $collection[0];
    }

    public function testSet(): void
    {
        $collection = new Collection();

        $collection[] = new Node('node1');

        $this->assertCount(1, $collection);
    }

    public function testSetStringKey(): void
    {
        $collection = new Collection();

        $this->expectException(\OutOfBoundsException::class);

        $collection['key'] = new Node('node1');
    }

    public function testSetIntegerKey(): void
    {
        $collection = new Collection();

        $this->expectException(\OutOfBoundsException::class);

        $collection[0] = new Node('node1');
    }

    public function testUnset(): void
    {
        $collection = new Collection([
            new Node('node1'),
        ]);

        unset($collection[0]);

        $this->assertEmpty($collection);
    }
}
