<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

interface StrategyInterface
{
    public function getIndex(CollectionInterface $collection): int;
}
