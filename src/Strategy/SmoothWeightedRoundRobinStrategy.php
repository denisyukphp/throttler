<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;

class SmoothWeightedRoundRobinStrategy extends ObjectSerializable implements Strategy
{
    /**
     * @var int[]
     */
    protected $weight;
    /**
     * @var int[]
     */
    protected $currentWeight;

    /**
     * @param Collection $collection
     *
     * @return int
     */
    public function getIndex(Collection $collection): int
    {
        if (!$this->weight || !$this->currentWeight) {
            foreach ($collection as $index => $node) {
                $this->weight[$index] = $this->currentWeight[$index] = $node->getWeight();
            }
        }

        $maxCurrentWeightIndex = $this->getMaxCurrentWeightIndex();

        $recalculatedCurrentWeight = $this->currentWeight[$maxCurrentWeightIndex] - $this->getSumWeight();

        $this->currentWeight[$maxCurrentWeightIndex] = $recalculatedCurrentWeight;

        foreach ($this->weight as $index => $weight) {
            $this->currentWeight[$index] += $weight;
        }

        return $maxCurrentWeightIndex;
    }

    /**
     * @return int
     *
     * @throws \LogicException
     */
    private function getMaxCurrentWeightIndex(): int
    {
        $maxCurrentWeight = max($this->currentWeight);

        $index = array_search($maxCurrentWeight, $this->currentWeight, true);

        if (false === $index) {
            throw new \LogicException('Cannot find max current weight index');
        }

        return $index;
    }

    /**
     * @return int
     */
    private function getSumWeight(): int
    {
        return array_sum($this->weight);
    }
}
