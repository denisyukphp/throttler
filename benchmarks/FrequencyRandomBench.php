<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\FrequencyRandomStrategy;
use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\ThrottlerInterface;

class FrequencyRandomBench
{
    private CollectionInterface $collection;
    private ThrottlerInterface $throttler;

    public function __construct()
    {
        $this->collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $this->throttler = new Throttler(
            new FrequencyRandomStrategy(),
        );
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
