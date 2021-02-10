<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

class RoundRobinStrategy implements StrategyInterface
{
    /**
     * @var CounterInterface
     */
    protected $counter;

    /**
     * @param CounterInterface $counter
     */
    public function __construct(CounterInterface $counter)
    {
        $this->counter = $counter;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return int
     */
    public function getIndex(CollectionInterface $collection): int
    {
        return $this->counter->increment() % $collection->getQuantity();
    }
}
