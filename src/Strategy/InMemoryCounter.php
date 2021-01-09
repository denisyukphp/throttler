<?php

namespace Orangesoft\Throttler\Strategy;

class InMemoryCounter implements Counter
{
    /**
     * @var int
     */
    protected $counter;

    /**
     * @param int $counter
     */
    public function __construct(int $counter = -1)
    {
        $this->counter = $counter;
    }

    /**
     * @return int
     */
    public function increment(): int
    {
        return ++$this->counter;
    }
}
