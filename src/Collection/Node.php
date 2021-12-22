<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Node implements NodeInterface
{
    public function __construct(
        private string $name,
        private int $weight = 0,
        private array $info = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getInfo(): array
    {
        return $this->info;
    }
}
