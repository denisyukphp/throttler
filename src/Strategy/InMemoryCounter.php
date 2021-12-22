<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

final class InMemoryCounter implements CounterInterface
{
    /**
     * @var array<string, int>
     */
    private array $counter = [];

    public function __construct(
        private int $start = 0,
    ) {
    }

    public function next(string $name = 'default'): int
    {
        if (!isset($this->counter[$name])) {
            $this->counter[$name] = $this->start;
        }

        return $this->counter[$name]++;
    }
}
