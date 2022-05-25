<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;

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

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if (!isset($context['cluster_name'])) {
            throw new \LogicException('Required parameter "cluster_name" is missing.');
        }

        if (!isset($this->clusterNames[$context['cluster_name']])) {
            throw new \LogicException(
                sprintf('Cluster name "%s" is undefined.', $context['cluster_name'])
            );
        }

        /** @var StrategyInterface $strategy */
        $strategy = $this->strategies[$this->clusterNames[$context['cluster_name']]];

        return $strategy->getNode($collection, $context);
    }
}
