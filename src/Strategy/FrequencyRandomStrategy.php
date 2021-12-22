<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

final class FrequencyRandomStrategy implements StrategyInterface
{
    public function __construct(
        private float $frequency = 0.8,
        private float $depth = 0.2,
    ) {
    }

    public function getIndex(CollectionInterface $collection, array $context = []): int
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes must not be empty.');
        }

        $total = count($collection);
        $low = (int) ceil($this->depth * $total);
        $high = $low + ((1 < $total) ? 1 : 0);

        $index = $this->isChance($this->frequency) ? mt_rand(1, $low) : mt_rand($high, $total);

        return $index - 1;
    }

    private function isChance(float $frequency): bool
    {
        return $frequency * 100 >= mt_rand(1, 100);
    }
}
