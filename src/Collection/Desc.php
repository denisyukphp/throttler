<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Desc
{
    public function __invoke(NodeInterface $a, NodeInterface $b): int
    {
        return $b->getWeight() - $a->getWeight();
    }
}
