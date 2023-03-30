<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;
use Orangesoft\Throttler\Collection\Node;

final class FrequencyRandomStrategy implements StrategyInterface
{
    public function __construct(
        private float $frequency = 0.8,
        private float $depth = 0.2,
    ) {
    }

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if ($collection->isEmpty()) {
            throw new EmptyCollectionException();
        }

        $total = \count($collection);
        $low = (int) ceil($this->depth * $total);
        $high = $low + ((1 < $total) ? 1 : 0);

        $index = $this->isChance($this->frequency) ? mt_rand(1, $low) : mt_rand($high, $total);

        return $collection->getNode($index - 1);
    }

    private function isChance(float $frequency): bool
    {
        return $frequency * 100 >= mt_rand(1, 100);
    }
}
