<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Exception\UnweightedCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class WeightedRoundRobinStrategy implements StrategyInterface
{
    private int $gcd = 0;
    private int $maxWeight = 0;
    private int $currentWeight = 0;

    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        if (!$collection->isWeighted()) {
            throw new UnweightedCollectionException();
        }

        if (0 === $this->gcd) {
            $this->gcd = $this->calculateGcd($collection);
        }

        if (0 === $this->maxWeight) {
            $this->maxWeight = $this->calculateMaxWeight($collection);
        }

        while (true) {
            $index = $this->counter->next($context['counter_name'] ?? self::class) % \count($collection);

            if (0 === $index) {
                $this->currentWeight -= $this->gcd;

                if (0 >= $this->currentWeight) {
                    $this->currentWeight = $this->maxWeight;
                }
            }

            $node = $collection->getNode($index);

            if ($node->weight >= $this->currentWeight) {
                return $node;
            }
        }
    }

    private function calculateGcd(CollectionInterface $collection): int
    {
        $gcd = 0;

        /** @var Node $node */
        foreach ($collection as $node) {
            $gcd = GcdCalculator::calculate($gcd, $node->weight);
        }

        return $gcd;
    }

    private function calculateMaxWeight(CollectionInterface $collection): int
    {
        $maxWeight = 0;

        /** @var Node $node */
        foreach ($collection as $node) {
            if ($node->weight >= $maxWeight) {
                $maxWeight = $node->weight;
            }
        }

        return $maxWeight;
    }
}
