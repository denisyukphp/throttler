<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Desc
{
    public function __invoke(Node $a, Node $b): int
    {
        return $b->weight - $a->weight;
    }
}
