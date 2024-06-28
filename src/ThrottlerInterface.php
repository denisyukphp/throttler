<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

interface ThrottlerInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function pick(CollectionInterface $collection, array $context = []): NodeInterface;
}
