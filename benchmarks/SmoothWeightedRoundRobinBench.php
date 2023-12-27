<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler;
use Orangesoft\Throttler\ThrottlerInterface;

final class SmoothWeightedRoundRobinBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new InMemoryCollection([
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ]);

        $this->throttler = new SmoothWeightedRoundRobinThrottler();
    }

    /**
     * @Revs(1000)
     *
     * @Iterations(5)
     */
    public function benchSmoothWeightedRoundRobin(): void
    {
        $this->throttler->pick($this->collection);
    }
}
