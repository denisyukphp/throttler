<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

final class WeightedRandomThrottler implements ThrottlerInterface
{
    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.');
        }

        $currentWeight = 0;
        $sumWeight = array_sum(array_map(static fn (NodeInterface $node): int => $node->getWeight(), $collection->toArray()));
        $randomWeight = mt_rand(1, $sumWeight);

        foreach ($collection as $node) {
            if (0 == $node->getWeight()) {
                throw new \RuntimeException('All nodes in the collection must be weighted.');
            }

            $currentWeight += $node->getWeight();

            if ($randomWeight <= $currentWeight) {
                return $node;
            }
        }

        throw new \RuntimeException('You never will catch this exception.');
    }
}
