<?php

namespace Orangesoft\Throttler\Strategy;

final class InMemoryCounter implements CounterInterface
{
    /**
     * @var int
     */
    private $counter;

    public function __construct(int $counter = 0)
    {
        $this->counter = $counter;
    }

    public function increment(string $key = 'default'): int
    {
        return $this->counter++;
    }
}
