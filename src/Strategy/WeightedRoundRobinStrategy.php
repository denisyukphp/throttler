<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\NotWeightedCollectionException;

class WeightedRoundRobinStrategy implements StrategyInterface
{
    /**
     * @var CounterInterface
     */
    protected $counter;
    /**
     * @var int
     */
    private $currentWeight = 0;
    /**
     * @var int
     */
    private $maxWeight = 0;
    /**
     * @var int
     */
    private $gcd = 0;

    /**
     * @param CounterInterface $counter
     */
    public function __construct(CounterInterface $counter)
    {
        $this->counter = $counter;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return int
     *
     * @throws NotWeightedCollectionException
     */
    public function getIndex(CollectionInterface $collection): int
    {
        $maxWeight = $this->getMaxWeight($collection);
        $gcd = $this->getGreatestCommonDivisor($collection);

        if (0 === $maxWeight || 0 === $gcd) {
            throw new NotWeightedCollectionException('Add at least one weighted node to collection');
        }

        while (true) {
            $index = $this->counter->increment() % $collection->getQuantity();

            if (0 === $index) {
                $this->currentWeight -= $gcd;

                if (0 >= $this->currentWeight) {
                    $this->currentWeight = $maxWeight;
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
     * @param CollectionInterface $collection
     *
     * @return int
     */
    private function getMaxWeight(CollectionInterface $collection): int
    {
        if (!$this->maxWeight) {
            foreach ($collection as $node) {
                if ($node->getWeight() >= $this->maxWeight) {
                    $this->maxWeight = $node->getWeight();
                }
            }
        }

        return $this->maxWeight;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return int
     */
    private function getGreatestCommonDivisor(CollectionInterface $collection): int
    {
        if (!$this->gcd) {
            foreach ($collection as $node) {
                $this->gcd = GcdCalculator::calculate($this->gcd, $node->getWeight());
            }
        }

        return $this->gcd;
    }
}
