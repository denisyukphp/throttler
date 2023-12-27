<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection\Sort;

use Orangesoft\Throttler\Collection\NodeInterface;

final class Asc
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $a->getWeight() - $b->getWeight();
    }
}
