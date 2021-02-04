<?php

namespace Orangesoft\Throttler\Tests;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\ThrottlerInterface;

class ThrottlerTest extends TestCase
{
    public function testNext(): void
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $throttler = new Throttler(
            new Collection($nodes),
            new RoundRobinStrategy(
                new InMemoryCounter()
            )
        );

        $next = $throttler->next();

        $this->assertInstanceOf(ThrottlerInterface::class, $throttler);
        $this->assertInstanceOf(Node::class, $next);
        $this->assertSame('node1', $next->getName());
    }

    public function testEmpty(): void
    {
        $throttler = new Throttler(
            new Collection(),
            new RandomStrategy()
        );

        $this->expectException(EmptyCollectionException::class);

        $throttler->next();
    }
}
