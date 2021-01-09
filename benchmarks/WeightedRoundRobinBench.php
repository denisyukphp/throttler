<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Orangesoft\Throttler\Throttler;

class WeightedRoundRobinBench
{
    private $loadBalancer;

    public function __construct()
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $this->loadBalancer = new Throttler(
            new Collection($nodes),
            new WeightedRoundRobinStrategy(
                new InMemoryCounter()
            )
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchWeightedRoundRobin()
    {
        $this->loadBalancer->next();
    }
}
