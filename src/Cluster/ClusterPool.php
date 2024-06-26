<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Cluster;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\ThrottlerInterface;

final class ClusterPool implements ThrottlerInterface
{
    /**
     * @var array<string, ThrottlerInterface>
     */
    private array $throttlers = [];
    /**
     * @var array<string, string>
     */
    private array $clusterNames = [];

    public function __construct(ClusterSet ...$clusterSets)
    {
        foreach ($clusterSets as $clusterSet) {
            $id = $clusterSet->throttler::class;
            $this->throttlers[$id] = $clusterSet->throttler;

            foreach ($clusterSet->clusterNames as $clusterName) {
                if (isset($this->clusterNames[$clusterName])) {
                    throw new \UnexpectedValueException(sprintf('The cluster "%s" has already been added.', $clusterName)); // @codeCoverageIgnore
                }

                $this->clusterNames[$clusterName] = $id;
            }
        }
    }

    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if (!isset($context['cluster'])) {
            throw new \RuntimeException('Required parameter "cluster" is missing.'); // @codeCoverageIgnore
        }

        if (!\is_string($context['cluster'])) {
            throw new \RuntimeException(sprintf('The parameter "cluster" must be as a string, %s given.', get_debug_type($context['cluster']))); // @codeCoverageIgnore
        }

        if (!isset($this->clusterNames[$context['cluster']])) {
            throw new \RuntimeException(sprintf('The cluster "%s" is undefined.', $context['cluster'])); // @codeCoverageIgnore
        }

        $throttler = $this->throttlers[$this->clusterNames[$context['cluster']]];

        return $throttler->pick($collection, $context);
    }
}
