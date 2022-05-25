<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;

interface StrategyInterface
{
    public function getNode(CollectionInterface $collection, array $context = []): Node;
}
