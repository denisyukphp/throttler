<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class RoundRobinStrategy implements StrategyInterface
{
    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        $index = $this->counter->next($context['counter_name'] ?? self::class) % \count($collection);

        return $collection->getNode($index);
    }
}
