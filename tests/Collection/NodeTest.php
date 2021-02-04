<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;

class NodeTest extends TestCase
{
    public function testNode(): void
    {
        $node = new Node('node1', 4);

        $this->assertSame('node1', $node->getName());
        $this->assertSame(4, $node->getWeight());
    }
}
