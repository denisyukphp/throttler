<?php

namespace Orangesoft\Throttler\Collection;

class Asc implements Comparator
{
    /**
     * @param Node $a
     * @param Node $b
     *
     * @return int
     */
    public function __invoke(Node $a, Node $b): int
    {
        return $a->getWeight() - $b->getWeight();
    }
}
