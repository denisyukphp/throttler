<?php

namespace Orangesoft\Throttler\Collection;

interface ComparatorInterface
{
    /**
     * @param NodeInterface $a
     * @param NodeInterface $b
     *
     * @return int
     */
    public function __invoke(NodeInterface $a, NodeInterface $b): int;
}
