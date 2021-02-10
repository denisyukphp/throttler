<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

class RandomStrategy implements StrategyInterface
{
    /**
     * @param CollectionInterface $collection
     *
     * @return int
     */
    public function getIndex(CollectionInterface $collection): int
    {
        $index = mt_rand(1, $collection->getQuantity());

        return $index - 1;
    }
}
