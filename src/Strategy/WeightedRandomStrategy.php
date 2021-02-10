<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\NotWeightedCollectionException;

class WeightedRandomStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $sumWeight = 0;

    /**
     * @param CollectionInterface $collection
     *
     * @return int
     *
     * @throws NotWeightedCollectionException
     */
    public function getIndex(CollectionInterface $collection): int
    {
        $currentWeight = 0;

        $sumWeight = $this->getSumWeight($collection);

        if (0 === $sumWeight) {
            throw new NotWeightedCollectionException('Add at least one weighted node to collection');
        }

        $offset = mt_rand(1, $sumWeight);

        foreach ($collection as $index => $node) {
            $currentWeight += $node->getWeight();

            if ($offset <= $currentWeight) {
                return $index;
            }
        }

        throw new \RuntimeException('You never will catch this exception');
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return int
     */
    private function getSumWeight(CollectionInterface $collection): int
    {
        if (!$this->sumWeight) {
            foreach ($collection as $node) {
                $this->sumWeight += $node->getWeight();
            }
        }

        return $this->sumWeight;
    }
}
