<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

interface StrategyInterface
{
    /**
     * @param CollectionInterface $collection
     *
     * @return int
     */
    public function getIndex(CollectionInterface $collection): int;
}
