<?php

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\Collection;

class FrequencyRandomStrategy implements Strategy
{
    /**
     * @var int
     */
    protected $frequency;
    /**
     * @var int
     */
    protected $depth;

    /**
     * @param int $frequency
     * @param int $depth
     */
    public function __construct(int $frequency = 80, int $depth = 20)
    {
        $this->frequency = $frequency;
        $this->depth = $depth;
    }

    /**
     * @param Collection $collection
     *
     * @return int
     */
    public function getIndex(Collection $collection): int
    {
        $quantity = $collection->getQuantity();

        $offset = $this->getOffset($this->depth, $quantity);
        $nextOffset = $this->getNextOffset($offset, $quantity);

        $index = $this->isChance($this->frequency) ? rand(1, $offset) : rand($nextOffset, $quantity);

        return $index - 1;
    }

    /**
     * @param int $depth
     * @param int $quantity
     *
     * @return int
     */
    private function getOffset(int $depth, int $quantity): int
    {
        return ceil($depth * ($quantity / 100));
    }

    /**
     * @param int $offset
     * @param int $quantity
     *
     * @return int
     */
    private function getNextOffset(int $offset, int $quantity): int
    {
        return $offset + ((1 < $quantity) ? 1 : 0);
    }

    /**
     * @param int $frequency
     *
     * @return bool
     */
    private function isChance(int $frequency): bool
    {
        return $frequency >= rand(1, 100);
    }
}
