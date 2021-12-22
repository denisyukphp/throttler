<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;

final class ClusterDetermineStrategy implements StrategyInterface
{
    /**
     * @var array<int, StrategyInterface>
     */
    private array $strategies = [];
    /**
     * @var array<string, int>
     */
    private array $clusterNames = [];

    public function __construct(ClusterSet ...$clusterSets)
    {
        foreach ($clusterSets as $key => $clusterSet) {
            $this->strategies[$key] = $clusterSet->strategy;

            foreach ($clusterSet->clusterNames as $clusterName) {
                if (isset($this->clusterNames[$clusterName])) {
                    throw new \InvalidArgumentException(
                        sprintf('Cluster "%s" has already been added.', $clusterName)
                    );
                }

                $this->clusterNames[$clusterName] = $key;
            }
        }
    }

    public function getIndex(CollectionInterface $collection, array $context = []): int
    {
        if (!isset($context['cluster_name'])) {
            throw new \RuntimeException('Required parameter "cluster_name" is missing.');
        }

        if (!isset($this->clusterNames[$context['cluster_name']])) {
            throw new \RuntimeException(
                sprintf('Cluster name "%s" is undefined.', $context['cluster_name'])
            );
        }

        /** @var StrategyInterface $strategy */
        $strategy = $this->strategies[$this->clusterNames[$context['cluster_name']]];

        return $strategy->getIndex($collection, $context);
    }
}
