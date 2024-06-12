<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests;

use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;
use PHPUnit\Framework\TestCase;

final class WeightedRoundRobinThrottlerTest extends TestCase
{
    public function testWeightedRoundRobinAlgorithm(): void
    {
        $throttler = new WeightedRoundRobinThrottler(new InMemoryCounter());
        $collection = new InMemoryCollection([
            new Node('192.168.0.1', 5),
            new Node('192.168.0.2', 1),
            new Node('192.168.0.3', 1),
        ]);

        $expectedNodes = [
            '192.168.0.1',
            '192.168.0.1',
            '192.168.0.1',
            '192.168.0.1',
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];
        $actualNodes = [];

        for ($i = 0; $i < 7; ++$i) {
            $actualNodes[] = $throttler->pick($collection);
        }

        $this->assertSame($expectedNodes, array_map(static fn (NodeInterface $actualNode): string => $actualNode->getName(), $actualNodes));
    }
}
