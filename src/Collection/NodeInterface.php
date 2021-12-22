<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

interface NodeInterface
{
    public function getName(): string;

    public function getWeight(): int;

    public function getInfo(): array;
}
