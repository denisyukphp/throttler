<?php

namespace Orangesoft\Throttler\Collection;

final class Sorter
{
    public function sort(CollectionInterface $collection, ComparatorInterface $comparator): CollectionInterface
    {
        $nodes = $collection->toArray();

        usort($nodes, $comparator);

        return new Collection($nodes);
    }
}
