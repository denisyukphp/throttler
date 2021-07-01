<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\NodeInterface;

interface ThrottlerInterface
{
    public function next(): NodeInterface;
}
