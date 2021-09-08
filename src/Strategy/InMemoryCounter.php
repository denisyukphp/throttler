<?php

namespace Orangesoft\Throttler\Strategy;

final class InMemoryCounter implements CounterInterface
{
    /**
     * @var int
     */
    private $start;
    /**
     * @var array<string, int>
     */
    private $counter = [];

    public function __construct(int $start = 0)
    {
        $this->start = $start;
    }

    public function increment(string $key = 'default'): int
    {
        if (!isset($this->counter[$key])) {
            $this->counter[$key] = $this->start;
        }

        return $this->counter[$key]++;
    }
}
