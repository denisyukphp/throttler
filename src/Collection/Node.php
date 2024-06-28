<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Node implements NodeInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private string $name,
        private int $weight = 0,
        private array $payload = [],
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

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
