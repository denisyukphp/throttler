<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\CollectionInterface;

class CollectionSerializableTest extends TestCase
{
    public function testSerializable(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
            new Node('node4'),
        ];

        $collection = new Collection($nodes);

        $serialized = serialize($collection);

        $this->assertIsString($serialized);

        /** @var CollectionInterface $unserializedCollection */
        $unserializedCollection = unserialize($serialized);

        $this->assertInstanceOf(CollectionInterface::class, $unserializedCollection);

        foreach ($unserializedCollection as $node) {
            $this->assertInstanceOf(NodeInterface::class, $node);
        }
    }
}
