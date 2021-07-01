<?php

namespace Orangesoft\Throttler\Collection;

final class Desc implements ComparatorInterface
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $b->getWeight() - $a->getWeight();
    }
}
