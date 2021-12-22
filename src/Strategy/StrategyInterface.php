<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

interface StrategyInterface
{
    public function getIndex(CollectionInterface $collection, array $context = []): int;
}
