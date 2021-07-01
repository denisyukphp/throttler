<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

final class RoundRobinStrategy implements StrategyInterface
{
    /**
     * @var CounterInterface
     */
    private $counter;

    public function __construct(CounterInterface $counter)
    {
        $this->counter = $counter;
    }

    public function getIndex(CollectionInterface $collection): int
    {
        return $this->counter->increment() % $collection->getQuantity();
    }
}
