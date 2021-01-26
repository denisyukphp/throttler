<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\Node;

interface ThrottlerInterface
{
    /**
     * @return Node
     */
    public function next(): Node;
}
