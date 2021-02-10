<?php

namespace Orangesoft\Throttler\Collection;

class Asc implements ComparatorInterface
{
    /**
     * @param NodeInterface $a
     * @param NodeInterface $b
     *
     * @return int
     */
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $a->getWeight() - $b->getWeight();
    }
}
