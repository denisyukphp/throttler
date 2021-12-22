<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Throttler;
use PHPUnit\Framework\TestCase;

class ThrottlerTest extends TestCase
{
    public function testPick(): void
    {
        $throttler = new Throttler(
            new RandomStrategy()
        );

        $expectedNode = new Node('node1');

        $collection = (new Collection())->addNode($expectedNode);

        $this->assertSame($expectedNode, $throttler->pick($collection));
    }
}
