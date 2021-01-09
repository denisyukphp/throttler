<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Throttler;

class RandomBench
{
    private $loadBalancer;

    public function __construct()
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $this->loadBalancer = new Throttler(
            new Collection($nodes),
            new RandomStrategy()
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRandom()
    {
        $this->loadBalancer->next();
    }
}
