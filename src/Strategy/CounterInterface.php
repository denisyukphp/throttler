<?php

namespace Orangesoft\Throttler\Strategy;

interface CounterInterface
{
    public function increment(string $key = 'default'): int;
}
