<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Benchmarks;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\Throttler;

class RandomBench
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
            new RandomStrategy(),
        );
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchRandom(): void
    {
        $this->throttler->pick($this->collection);
    }
}
