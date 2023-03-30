<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class WeightedRandomStrategy implements StrategyInterface
{
    private int $sumWeight = 0;

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException();
        }

        $currentWeight = 0;

        if (0 === $this->sumWeight) {
            $this->sumWeight = $this->calculateSumWeight($collection);
        }

        $randomWeight = mt_rand(1, $this->sumWeight);

        /** @var array<int, Node> $collection */
        foreach ($collection as $index => $node) {
            $currentWeight += $node->weight;

            if ($randomWeight <= $currentWeight) {
                return $collection->getNode($index);
            }
        }

        throw new \RuntimeException('You never will catch this exception.');
    }

    private function calculateSumWeight(CollectionInterface $collection): int
    {
        $sumWeight = 0;

        /** @var Node $node */
        foreach ($collection as $node) {
            $sumWeight += $node->weight;
        }

        return $sumWeight;
    }
}
