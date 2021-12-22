<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Asc
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $a->getWeight() - $b->getWeight();
    }
}
