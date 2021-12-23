<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Node
{
    public function __construct(
        public readonly string $name,
        public readonly int $weight = 0,
        public readonly array $info = [],
    ) {
    }
}
