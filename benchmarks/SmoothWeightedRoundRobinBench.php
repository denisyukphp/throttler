<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;
use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\ThrottlerInterface;

class SmoothWeightedRoundRobinBench
{
    /**
     * @var ThrottlerInterface
     */
    private $throttler;

    public function __construct()
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $this->throttler = new Throttler(
            new Collection($nodes),
            new SmoothWeightedRoundRobinStrategy()
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSmoothWeightedRoundRobin()
    {
        $this->throttler->next();
    }
}
