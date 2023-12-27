<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\FrequencyRandomThrottler;
use Orangesoft\Throttler\ThrottlerInterface;

final class FrequencyRandomBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new InMemoryCollection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->throttler = new FrequencyRandomThrottler();
    }

    /**
     * @Revs(1000)
     *
     * @Iterations(5)
     */
    public function benchFrequencyRandom(): void
    {
        $this->throttler->pick($this->collection);
    }
}
