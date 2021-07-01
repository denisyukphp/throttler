<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;

final class WeightedRoundRobinStrategy implements StrategyInterface
{
    /**
     * @var CounterInterface
     */
    private $counter;
    /**
     * @var int
     */
    private $gcd = 0;
    /**
     * @var int
     */
    private $maxWeight = 0;
    /**
     * @var int
     */
    private $currentWeight = 0;

    public function __construct(CounterInterface $counter)
    {
        $this->counter = $counter;
    }

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

        if (0 === $this->gcd) {
            $this->gcd = $this->calculateGcd($collection);
        }

        if (0 === $this->maxWeight) {
            $this->maxWeight = $this->calculateMaxWeight($collection);
        }

        while (true) {
            $index = $this->counter->increment() % $collection->getQuantity();

            if (0 === $index) {
                $this->currentWeight -= $this->gcd;

                if (0 >= $this->currentWeight) {
                    $this->currentWeight = $this->maxWeight;
                }
            }

            $node = $collection->getNode($index);

            if ($node->getWeight() >= $this->currentWeight) {
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
    private function calculateGcd(CollectionInterface $collection): int
    {
        $gcd = 0;

        foreach ($collection as $node) {
            $gcd = GcdCalculator::calculate($gcd, $node->getWeight());
        }

        return $gcd;
    }

    /**
     * @param CollectionInterface|NodeInterface[] $collection
     *
     * @return int
     */
    private function calculateMaxWeight(CollectionInterface $collection): int
    {
        $maxWeight = 0;

        foreach ($collection as $node) {
            if ($node->getWeight() >= $maxWeight) {
                $maxWeight = $node->getWeight();
            }
        }

        return $maxWeight;
    }
}
