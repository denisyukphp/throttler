<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

interface ThrottlerInterface
{
    /**
     * @return Node
     */
    public function next(): Node;
}
