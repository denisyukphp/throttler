<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

use Orangesoft\Throttler\ThrottlerInterface;

final class Cluster implements ClusterInterface
{
    public function __construct(
        private string $name,
        private CollectionInterface $collection,
    ) {
    }

    public function balance(ThrottlerInterface $throttler, array $context = []): NodeInterface
    {
        return $throttler->pick($this->collection, array_merge($context, [
            'cluster_name' => $this->name,
        ]));
    }
}
