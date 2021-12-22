<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

final class RoundRobinStrategy implements StrategyInterface
{
    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function getIndex(CollectionInterface $collection, array $context = []): int
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes must not be empty.');
        }

        return $this->counter->next($context['counter_name'] ?? self::class) % count($collection);
    }
}
