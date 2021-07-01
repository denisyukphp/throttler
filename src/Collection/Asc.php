<?php

namespace Orangesoft\Throttler\Collection;

final class Asc implements ComparatorInterface
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $a->getWeight() - $b->getWeight();
    }
}
