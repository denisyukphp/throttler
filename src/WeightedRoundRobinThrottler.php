<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\CounterInterface;

final class WeightedRoundRobinThrottler implements ThrottlerInterface
{
    /**
     * @var array<string, int>
     */
    private array $gcdWeight = [];
    /**
     * @var array<string, int>
     */
    private array $maxWeight = [];
    /**
     * @var array<string, int>
     */
    private array $currentWeight = [];

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
            throw new \RuntimeException(sprintf('The parameter "counter" must be as a string, %s given.', get_debug_type($context['counter']))); // @codeCoverageIgnore
        }

        $counter = $context['counter'] ?? spl_object_hash($collection);

        if (!isset($this->gcdWeight[$counter]) || !isset($this->maxWeight[$counter]) || !isset($this->currentWeight[$counter])) {
            $this->gcdWeight[$counter] = 0;
            $this->maxWeight[$counter] = 0;
            $this->currentWeight[$counter] = 0;
        }

        if (0 === $this->gcdWeight[$counter] || 0 === $this->maxWeight[$counter] || 0 === $this->currentWeight[$counter]) {
            foreach ($collection as $node) {
                if (0 === $node->getWeight()) {
                    throw new \RuntimeException('All nodes in the collection must be weighted.'); // @codeCoverageIgnore
                }

                $this->gcdWeight[$counter] = gcd($this->gcdWeight[$counter], $node->getWeight());
                $this->maxWeight[$counter] = max($this->maxWeight[$counter], $node->getWeight());
            }
        }

        while (true) {
            $key = $this->counter->next($counter) % \count($collection);

            if (0 == $key) {
                $this->currentWeight[$counter] -= $this->gcdWeight[$counter];

                if (0 >= $this->currentWeight[$counter]) {
                    $this->currentWeight[$counter] = $this->maxWeight[$counter];
                }
            }

            $node = $collection->get($key);

            if ($node->getWeight() >= $this->currentWeight[$counter]) {
                return $node;
            }
        }
    }
}
