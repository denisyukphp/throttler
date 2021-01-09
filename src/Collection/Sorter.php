<?php

namespace Orangesoft\Throttler\Collection;

class Sorter
{
    /**
     * @param Collection $collection
     * @param Comparator $comparator
     *
     * @return Collection
     */
    public function sort(Collection $collection, Comparator $comparator): Collection
    {
        $nodes = iterator_to_array($collection->getIterator(), false);

        usort($nodes, $comparator);

        return new Collection($nodes);
    }
}
