<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Cluster;

use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\ThrottlerInterface;

interface ClusterInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function balance(ThrottlerInterface $throttler, array $context = []): NodeInterface;
}
