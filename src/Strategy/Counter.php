<?php

namespace Orangesoft\Throttler\Strategy;

interface Counter
{
    /**
     * @param string $key
     *
     * @return int
     */
    public function increment(string $key = 'default'): int;
}
