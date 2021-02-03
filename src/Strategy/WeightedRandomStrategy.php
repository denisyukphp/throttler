<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Exception\NotWeightedCollectionException;

class WeightedRandomStrategy implements Strategy
{
    /**
     * @var int
     */
    private $sumWeight = 0;

    /**
     * @param Collection $collection
     *
     * @return int
     *
     * @throws NotWeightedCollectionException
     */
    public function getIndex(Collection $collection): int
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
     * @param Collection $collection
     *
     * @return int
     */
    private function getSumWeight(Collection $collection): int
    {
        if (!$this->sumWeight) {
            foreach ($collection as $node) {
                $this->sumWeight += $node->getWeight();
            }
        }

        return $this->sumWeight;
    }
}
