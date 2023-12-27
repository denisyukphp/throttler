<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\CounterInterface;

final class WeightedRoundRobinThrottler implements ThrottlerInterface
{
    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.');
        }

        $gcdWeight = 0;
        $maxWeight = 0;
        $currentWeight = 0;

        foreach ($collection as $node) {
            if (0 == $node->getWeight()) {
                throw new \RuntimeException('All nodes in the collection must be weighted.');
            }

            $gcdWeight = gcd($gcdWeight, $node->getWeight());
            $maxWeight = max($maxWeight, $node->getWeight());
        }

        while (true) {
            $counter = $context['counter'] ?? spl_object_hash($collection);
            $key = $this->counter->next($counter) % \count($collection);

            if (0 == $key) {
                $currentWeight -= $gcdWeight;

                if (0 >= $currentWeight) {
                    $currentWeight = $maxWeight;
                }
            }

            $node = $collection->get($key);

            if ($node->getWeight() >= $currentWeight) {
                return $node;
            }
        }
    }
}
