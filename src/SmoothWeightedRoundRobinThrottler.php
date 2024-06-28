<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

final class SmoothWeightedRoundRobinThrottler implements ThrottlerInterface
{
    /**
     * @var array<string, array<int, int>>
     */
    private array $weights = [];
    /**
     * @var array<string, array<int, int>>
     */
    private array $currentWeights = [];

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

        if (!isset($this->weights[$counter]) || !isset($this->currentWeights[$counter])) {
            $this->weights[$counter] = [];
            $this->currentWeights[$counter] = [];
        }

        if (0 === \count($this->weights[$counter]) || 0 === \count($this->currentWeights[$counter])) {
            foreach ($collection as $key => $node) {
                if (0 === $node->getWeight()) {
                    throw new \RuntimeException('All nodes in the collection must be weighted.'); // @codeCoverageIgnore
                }

                $this->weights[$counter][$key] = $node->getWeight();
                $this->currentWeights[$counter][$key] = $node->getWeight();
            }
        }

        if (0 === \count($this->currentWeights[$counter])) {
            throw new \RuntimeException('Current weights are empty.'); // @codeCoverageIgnore
        }

        $maxCurrentWeight = max($this->currentWeights[$counter]);

        if (false === $maxCurrentWeightKey = array_search($maxCurrentWeight, $this->currentWeights[$counter], true)) {
            throw new \LogicException('Couldn\'t find max current weight index.'); // @codeCoverageIgnore
        }

        $this->currentWeights[$counter][$maxCurrentWeightKey] -= array_sum($this->weights[$counter]);

        foreach ($this->weights[$counter] as $key => $weight) {
            $this->currentWeights[$counter][$key] += $weight;
        }

        return $collection->get($maxCurrentWeightKey);
    }
}
