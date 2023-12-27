<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\ThrottlerInterface;

final class RoundRobinBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new InMemoryCollection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->throttler = new RoundRobinThrottler(
            new InMemoryCounter(),
        );
    }

    /**
     * @Revs(1000)
     *
     * @Iterations(5)
     */
    public function benchRoundRobin(): void
    {
        $this->throttler->pick($this->collection);
    }
}
