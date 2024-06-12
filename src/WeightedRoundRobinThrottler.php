<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\CounterInterface;

final class WeightedRoundRobinThrottler implements ThrottlerInterface
{
    private int $gcdWeight = 0;
    private int $maxWeight = 0;
    private int $currentWeight = 0;

    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.'); // @codeCoverageIgnore
        }

        if (isset($context['counter']) && !\is_string($context['counter'])) {
            throw new \RuntimeException(sprintf('The parameter "counter" must be as string, %s given.', get_debug_type($context['counter']))); // @codeCoverageIgnore
        }

        $counter = $context['counter'] ?? spl_object_hash($collection);

        foreach ($collection as $node) {
            if (0 == $node->getWeight()) {
                throw new \RuntimeException('All nodes in the collection must be weighted.'); // @codeCoverageIgnore
            }

            $this->gcdWeight = gcd($this->gcdWeight, $node->getWeight());
            $this->maxWeight = max($this->maxWeight, $node->getWeight());
        }

        while (true) {
            $key = $this->counter->next($counter) % \count($collection);

            if (0 == $key) {
                $this->currentWeight -= $this->gcdWeight;

                if (0 >= $this->currentWeight) {
                    $this->currentWeight = $this->maxWeight;
                }
            }

            $node = $collection->get($key);

            if ($node->getWeight() >= $this->currentWeight) {
                return $node;
            }
        }
    }
}
