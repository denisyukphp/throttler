<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;

class RoundRobinStrategy implements Strategy
{
    /**
     * @var Counter
     */
    protected $counter;

    /**
     * @param Counter $counter
     */
    public function __construct(Counter $counter)
    {
        $this->counter = $counter;
    }

    /**
     * @param Collection $collection
     *
     * @return int
     */
    public function getIndex(Collection $collection): int
    {
        return $this->counter->increment() % $collection->getQuantity();
    }
}
