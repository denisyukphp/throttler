<?php

namespace Orangesoft\Throttler\Strategy;

interface Counter
{
    /**
     * @return int
     */
    public function increment(): int;
}
