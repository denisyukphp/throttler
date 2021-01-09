<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\FrequencyRandomStrategy;
use Orangesoft\Throttler\Throttler;

class FrequencyRandomBench
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
            new FrequencyRandomStrategy()
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchFrequencyRandom()
    {
        $this->loadBalancer->next();
    }
}
