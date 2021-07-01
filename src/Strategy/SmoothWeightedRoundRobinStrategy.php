<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;

final class SmoothWeightedRoundRobinStrategy extends ObjectSerializable implements StrategyInterface
{
    /**
     * @var int[]
     */
    protected $weights = [];
    /**
     * @var int[]
     */
    protected $currentWeights = [];

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

        if (0 === count($this->weights) || 0 === count($this->currentWeights)) {
            foreach ($collection as $index => $node) {
                $this->weights[$index] = $this->currentWeights[$index] = $node->getWeight();
            }
        }

        $maxCurrentWeightIndex = $this->getMaxCurrentWeightIndex();

        $this->recalculateCurrentWeights($maxCurrentWeightIndex);

        return $maxCurrentWeightIndex;
    }

    private function getMaxCurrentWeightIndex(): int
    {
        $maxCurrentWeight = max($this->currentWeights);

        $index = array_search($maxCurrentWeight, $this->currentWeights, true);

        if (false === $index) {
            throw new \LogicException('Cannot find max current weight index');
        }

        return $index;
    }

    private function recalculateCurrentWeights(int $maxCurrentWeightIndex): void
    {
        $recalculatedCurrentWeight = $this->currentWeights[$maxCurrentWeightIndex] - $this->calculateSumWeight();

        $this->currentWeights[$maxCurrentWeightIndex] = $recalculatedCurrentWeight;

        foreach ($this->weights as $index => $weight) {
            $this->currentWeights[$index] += $weight;
        }
    }

    private function calculateSumWeight(): int
    {
        return array_sum($this->weights);
    }
}
