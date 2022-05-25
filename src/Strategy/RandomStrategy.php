<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class RandomStrategy implements StrategyInterface
{
    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        $index = mt_rand(0, count($collection) - 1);

        return $collection->getNode($index);
    }
}
