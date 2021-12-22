<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

final class ClusterSet
{
    /**
     * @param StrategyInterface $strategy
     * @param string[] $clusterNames
     */
    public function __construct(
        public readonly StrategyInterface $strategy,
        public readonly array $clusterNames,
    ) {
        if (0 === count($clusterNames)) {
            throw new \InvalidArgumentException('Cluster names must not be empty.');
        }
    }
}
