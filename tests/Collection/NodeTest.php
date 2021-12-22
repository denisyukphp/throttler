<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testName(): void
    {
        $node = new Node('node1');

        $this->assertSame('node1', $node->getName());
    }

    public function testWeight(): void
    {
        $node = new Node('node1', 4);

        $this->assertSame(4, $node->getWeight());
    }

    public function testInfo(): void
    {
        $node = new Node('node1', 4, ['a' => 'b']);

        $this->assertSame(['a' => 'b'], $node->getInfo());
    }
}
