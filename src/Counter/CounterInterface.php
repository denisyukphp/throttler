<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Counter;

interface CounterInterface
{
    public function next(string $name = 'default'): int;
}
