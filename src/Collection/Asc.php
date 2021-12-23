<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Asc
{
    public function __invoke(Node $a, Node $b): int
    {
        return $a->weight - $b->weight;
    }
}
