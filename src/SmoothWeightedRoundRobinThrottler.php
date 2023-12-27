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

    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.');
        }

        $counter = $context['counter'] ?? spl_object_hash($collection);

        if (!isset($this->weights[$counter]) || !isset($this->currentWeights[$counter])) {
            foreach ($collection as $key => $node) {
                if (0 == $node->getWeight()) {
                    throw new \RuntimeException('All nodes in the collection must be weighted.');
                }

                $this->weights[$counter][$key] = $this->currentWeights[$counter][$key] = $node->getWeight();
            }
        }

        uasort($this->currentWeights[$counter], static fn (int $a, int $b): int => $a <=> $b);
        $sumWeights = array_sum($this->weights[$counter]);
        $maxCurrentWeightKey = array_key_last($this->currentWeights[$counter]);
        $this->currentWeights[$counter][$maxCurrentWeightKey] -= $sumWeights;

        foreach ($this->weights[$counter] as $key => $weight) {
            $this->currentWeights[$counter][$key] += $weight;
        }

        return $collection->get($maxCurrentWeightKey);
    }
}
