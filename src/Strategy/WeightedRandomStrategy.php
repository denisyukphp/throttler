<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

final class WeightedRandomStrategy implements StrategyInterface
{
    private int $sumWeight = 0;

    public function getIndex(CollectionInterface $collection, array $context = []): int
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes must not be empty.');
        }

        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException('All nodes in the collection must be weighted.');
        }

        $currentWeight = 0;

        if (0 === $this->sumWeight) {
            $this->sumWeight = $this->calculateSumWeight($collection);
        }

        $randomWeight = mt_rand(1, $this->sumWeight);

        /** @var array<int, NodeInterface> $collection*/
        foreach ($collection as $index => $node) {
            $currentWeight += $node->getWeight();

            if ($randomWeight <= $currentWeight) {
                return $index;
            }
        }

        throw new \RuntimeException('You never will catch this exception.');
    }

    private function calculateSumWeight(CollectionInterface $collection): int
    {
        $sumWeight = 0;

        /** @var NodeInterface $node */
        foreach ($collection as $node) {
            $sumWeight += $node->getWeight();
        }

        return $sumWeight;
    }
}
