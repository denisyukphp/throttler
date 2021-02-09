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
    public function __construct(int $counter = 0)
    {
        $this->counter = $counter;
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function increment(string $key = 'default'): int
    {
        return $this->counter++;
    }
}
