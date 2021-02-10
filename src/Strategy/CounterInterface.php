<?php

namespace Orangesoft\Throttler\Strategy;

interface CounterInterface
{
    /**
     * @param string $key
     *
     * @return int
     */
    public function increment(string $key = 'default'): int;
}
