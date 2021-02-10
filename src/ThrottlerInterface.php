<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\NodeInterface;

interface ThrottlerInterface
{
    /**
     * @return NodeInterface
     */
    public function next(): NodeInterface;
}
