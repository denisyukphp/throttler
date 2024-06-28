<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

interface NodeInterface
{
    public function getName(): string;

    public function getWeight(): int;

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array;
}
