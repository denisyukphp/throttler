<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Cluster;

use Orangesoft\Throttler\ThrottlerInterface;

final class ClusterSet
{
    /**
     * @param string[] $clusterNames
     */
    public function __construct(
        public readonly ThrottlerInterface $throttler,
        public readonly array $clusterNames,
    ) {
    }
}
