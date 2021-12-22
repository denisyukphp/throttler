<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\Throttler;

class WeightedRoundRobinBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new Collection([
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ]);

        $this->throttler = new Throttler(
            new WeightedRoundRobinStrategy(
                new InMemoryCounter()
            )
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchWeightedRoundRobin(): void
    {
        $this->throttler->pick($this->collection);
    }
}
