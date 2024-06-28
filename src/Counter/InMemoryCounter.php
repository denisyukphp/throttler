<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Counter;

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

        $next = $this->counter[$name];
        ++$this->counter[$name];

        return $next;
    }
}
