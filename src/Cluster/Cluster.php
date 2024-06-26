<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Cluster;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\ThrottlerInterface;

final class Cluster implements ClusterInterface
{
    public function __construct(
        private string $name,
        private CollectionInterface $collection,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function balance(ThrottlerInterface $pool, array $context = []): NodeInterface
    {
        return $pool->pick($this->collection, array_merge($context, [
            'cluster' => $this->name,
        ]));
    }
}
