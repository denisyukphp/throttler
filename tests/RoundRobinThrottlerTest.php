<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests;

use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\RoundRobinThrottler;
use PHPUnit\Framework\TestCase;

final class RoundRobinThrottlerTest extends TestCase
{
    public function testRoundRobinAlgorithm(): void
    {
        $throttler = new RoundRobinThrottler(new InMemoryCounter());
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $expectedNodes = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];
        $actualNodes = [];

        for ($i = 0; $i < 6; ++$i) {
            $actualNodes[] = $throttler->pick($collection);
        }

        $this->assertSame($expectedNodes, array_map(static fn (NodeInterface $actualNode): string => $actualNode->getName(), $actualNodes));
    }
}
