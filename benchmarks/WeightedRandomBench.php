<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\WeightedRandomThrottler;

final class WeightedRandomBench
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

        $this->throttler = new WeightedRandomThrottler();
    }

    /**
     * @Revs(1000)
     *
     * @Iterations(5)
     */
    public function benchWeightedRandom(): void
    {
        $this->throttler->pick($this->collection);
    }
}
