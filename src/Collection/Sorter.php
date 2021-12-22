<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Sorter
{
    public function sort(CollectionInterface $collection, callable $callback): void
    {
        $nodes = $collection->toArray();

        usort($nodes, $callback);

        $collection->purge();

        foreach ($nodes as $node) {
            $collection->addNode($node);
        }
    }
}
