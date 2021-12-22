<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;

final class WeightedRoundRobinStrategy implements StrategyInterface
{
    private int $gcd = 0;
    private int $maxWeight = 0;
    private int $currentWeight = 0;

    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function getIndex(CollectionInterface $collection, array $context = []): int
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes must not be empty.');
        }

        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException('All nodes in the collection must be weighted.');
        }

        if (0 === $this->gcd) {
            $this->gcd = $this->calculateGcd($collection);
        }

        if (0 === $this->maxWeight) {
            $this->maxWeight = $this->calculateMaxWeight($collection);
        }

        while (true) {
            $index = $this->counter->next($context['counter_name'] ?? self::class) % count($collection);

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
    }

    private function calculateGcd(CollectionInterface $collection): int
    {
        $gcd = 0;

        /** @var NodeInterface $node */
        foreach ($collection as $node) {
            $gcd = GcdCalculator::calculate($gcd, $node->getWeight());
        }

        return $gcd;
    }

    private function calculateMaxWeight(CollectionInterface $collection): int
    {
        $maxWeight = 0;

        /** @var NodeInterface $node */
        foreach ($collection as $node) {
            if ($node->getWeight() >= $maxWeight) {
                $maxWeight = $node->getWeight();
            }
        }

        return $maxWeight;
    }
}
