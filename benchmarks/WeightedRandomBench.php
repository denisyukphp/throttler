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
            new Node('192.168.0.1', 5),
            new Node('192.168.0.2', 1),
            new Node('192.168.0.3', 1),
        ]);

        $this->throttler = new WeightedRandomThrottler();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchWeightedRandomAlgorithm(): void
    {
        $this->throttler->pick($this->collection);
    }
}
