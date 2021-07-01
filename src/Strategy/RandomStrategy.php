<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

final class RandomStrategy implements StrategyInterface
{
    public function getIndex(CollectionInterface $collection): int
    {
        $index = mt_rand(1, $collection->getQuantity());

        return $index - 1;
    }
}
