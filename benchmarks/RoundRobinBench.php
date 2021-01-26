<?php

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\ThrottlerInterface;

class RoundRobinBench
{
    /**
     * @var ThrottlerInterface
     */
    private $throttler;

    public function __construct()
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $this->throttler = new Throttler(
            new Collection($nodes),
            new RoundRobinStrategy(
                new InMemoryCounter()
            )
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRoundRobin()
    {
        $this->throttler->next();
    }
}
