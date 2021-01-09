<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;

class CollectionArrayAccessTest extends TestCase
{
    public function testExists()
    {
        $nodes = [
            new Node('node1'),
        ];

        $collection = new Collection($nodes);

        $this->assertTrue(isset($collection[0]));
    }

    public function testNotExists()
    {
        $collection = new Collection();

        $this->assertFalse(isset($collection[0]));
    }

    public function testGet()
    {
        $nodes = [
            new Node('node1'),
        ];

        $collection = new Collection($nodes);

        $this->assertSame('node1', $collection[0]->getName());
    }

    public function testGetNotExists()
    {
        $collection = new Collection();

        $this->expectException(\OutOfRangeException::class);

        $collection[0];
    }

    public function testSet()
    {
        $collection = new Collection();

        $collection[] = new Node('node1');

        $this->assertCount(1, $collection);
    }

    public function testSetWithKey()
    {
        $collection = new Collection();

        $collection[0] = new Node('node1');

        $this->assertNotEmpty($collection);
    }

    public function testWrongKey()
    {
        $collection = new Collection();

        $this->expectException(\OutOfBoundsException::class);

        $collection['key'] = new Node('node1');
    }

    public function testWrongValue()
    {
        $collection = new Collection();

        $this->expectException(\InvalidArgumentException::class);

        $collection[] = 'node1';
    }

    public function testUnset()
    {
        $nodes = [
            new Node('node1'),
        ];

        $collection = new Collection($nodes);

        unset($collection[0]);

        $this->assertEmpty($collection);
    }
}
