<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\WeightedRandomStrategy;
use Orangesoft\Throttler\Throttler;

class WeightedRandomBench
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
            new WeightedRandomStrategy()
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchWeightedRandom()
    {
        $this->loadBalancer->next();
    }
}
