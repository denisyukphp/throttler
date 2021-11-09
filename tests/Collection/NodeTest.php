<?php

namespace Orangesoft\Throttler\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;

class NodeTest extends TestCase
{
    public function testNode(): void
    {
        $node = new Node('node1', 4, ['a' => 'b']);

        $this->assertSame('node1', $node->getName());
        $this->assertSame(4, $node->getWeight());
        $this->assertSame('b', $node->getInfo()['a']);
    }
}
