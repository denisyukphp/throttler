<?php

namespace Orangesoft\Throttler\Collection;

interface Comparator
{
    /**
     * @param Node $a
     * @param Node $b
     *
     * @return int
     */
    public function __invoke(Node $a, Node $b): int;
}
