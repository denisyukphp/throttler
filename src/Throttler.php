<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\StrategyInterface;

final class Throttler implements ThrottlerInterface
{
    public function __construct(
        private StrategyInterface $strategy,
    ) {
    }

    public function pick(CollectionInterface $collection, array $context = []): Node
    {
        return $this->strategy->getNode($collection, $context);
    }
}
