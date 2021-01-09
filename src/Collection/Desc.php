<?php

namespace Orangesoft\Throttler\Collection;

class Desc implements Comparator
{
    /**
     * @param Node $a
     * @param Node $b
     *
     * @return int
     */
    public function __invoke(Node $a, Node $b): int
    {
        return $b->getWeight() - $a->getWeight();
    }
}
