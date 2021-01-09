<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;

class RandomStrategy implements Strategy
{
    /**
     * @param Collection $collection
     *
     * @return int
     */
    public function getIndex(Collection $collection): int
    {
        $index = rand(1, $collection->getQuantity());

        return $index - 1;
    }
}
