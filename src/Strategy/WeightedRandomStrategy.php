<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;

final class WeightedRandomStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $sumWeight = 0;

    /**
     * @param CollectionInterface|NodeInterface[] $collection
     *
     * @return int
     */
    public function getIndex(CollectionInterface $collection): int
    {
        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException('All nodes in the collection must be weighted');
        }

        $currentWeight = 0;

        if (0 === $this->sumWeight) {
            $this->sumWeight = $this->calculateSumWeight($collection);
        }

        $randomWeight = mt_rand(1, $this->sumWeight);

        foreach ($collection as $index => $node) {
            $currentWeight += $node->getWeight();

            if ($randomWeight <= $currentWeight) {
                return $index;
            }
        }

        throw new \RuntimeException('You never will catch this exception');
    }

    /**
     * @param CollectionInterface|NodeInterface[] $collection
     *
     * @return int
     */
    private function calculateSumWeight(CollectionInterface $collection): int
    {
        $sumWeight = 0;

        foreach ($collection as $node) {
            $sumWeight += $node->getWeight();
        }

        return $sumWeight;
    }
}
