<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

interface CounterInterface
{
    public function next(string $name = 'default'): int;
}
