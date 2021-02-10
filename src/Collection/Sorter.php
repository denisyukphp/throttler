<?php

namespace Orangesoft\Throttler\Collection;

class Sorter
{
    /**
     * @param CollectionInterface $collection
     * @param ComparatorInterface $comparator
     *
     * @return Collection
     */
    public function sort(CollectionInterface $collection, ComparatorInterface $comparator): Collection
    {
        $nodes = $collection->toArray();

        usort($nodes, $comparator);

        return new Collection($nodes);
    }
}
