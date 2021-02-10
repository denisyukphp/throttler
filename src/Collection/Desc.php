<?php

namespace Orangesoft\Throttler\Collection;

class Desc implements ComparatorInterface
{
    /**
     * @param NodeInterface $a
     * @param NodeInterface $b
     *
     * @return int
     */
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $b->getWeight() - $a->getWeight();
    }
}
