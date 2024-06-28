<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\RandomThrottler;
use Orangesoft\Throttler\ThrottlerInterface;

final class RandomBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);

        $this->throttler = new RandomThrottler();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRandomAlgorithm(): void
    {
        $this->throttler->pick($this->collection);
    }
}
