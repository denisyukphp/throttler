<?php

namespace Orangesoft\Throttler\Collection;

interface ComparatorInterface
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int;
}
