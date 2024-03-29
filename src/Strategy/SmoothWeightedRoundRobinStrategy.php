<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class SmoothWeightedRoundRobinStrategy implements StrategyInterface
{
    /**
     * @var array<int, int>
     */
    private array $weights = [];
    /**
     * @var array<int, int>
     */
    private array $currentWeights = [];

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException();
        }

        if (0 === \count($this->weights) || 0 === \count($this->currentWeights)) {
            /** @var array<int, Node> $collection */
            foreach ($collection as $index => $node) {
                $this->weights[$index] = $this->currentWeights[$index] = $node->weight;
            }
        }

        $maxCurrentWeightIndex = $this->getMaxCurrentWeightIndex();

        $this->recalculateCurrentWeights($maxCurrentWeightIndex);

        return $collection->getNode($maxCurrentWeightIndex);
    }

    private function getMaxCurrentWeightIndex(): int
    {
        $maxCurrentWeight = max($this->currentWeights);

        if (false === $index = array_search($maxCurrentWeight, $this->currentWeights, true)) {
            throw new \LogicException('Cannot find max current weight index.');
        }

        return $index;
    }

    private function recalculateCurrentWeights(int $maxCurrentWeightIndex): void
    {
        $recalculatedCurrentWeight = $this->currentWeights[$maxCurrentWeightIndex] - array_sum($this->weights);

        $this->currentWeights[$maxCurrentWeightIndex] = $recalculatedCurrentWeight;

        foreach ($this->weights as $index => $weight) {
            $this->currentWeights[$index] += $weight;
        }
    }
}
