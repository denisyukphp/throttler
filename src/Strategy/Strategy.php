<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;

interface Strategy
{
    /**
     * @param Collection $collection
     *
     * @return int
     */
    public function getIndex(Collection $collection): int;
}
