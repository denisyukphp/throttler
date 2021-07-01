<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

final class FrequencyRandomStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $frequency;
    /**
     * @var int
     */
    private $depth;

    public function __construct(int $frequency = 80, int $depth = 20)
    {
        $this->frequency = $frequency;
        $this->depth = $depth;
    }

    public function getIndex(CollectionInterface $collection): int
    {
        $total = $collection->getQuantity();

        $lowOffset = ceil($this->depth * ($total / 100));
        $highOffset = $lowOffset + ((1 < $total) ? 1 : 0);

        $index = $this->isChance($this->frequency) ? mt_rand(1, $lowOffset) : mt_rand($highOffset, $total);

        return $index - 1;
    }

    private function isChance(int $frequency): bool
    {
        return $frequency >= mt_rand(1, 100);
    }
}
