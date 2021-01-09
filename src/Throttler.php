<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Strategy\Strategy;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

final class Throttler implements ThrottlerInterface
{
    /**
     * @var Collection
     */
    private $collection;
    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @param Collection $collection
     * @param Strategy $strategy
     */
    public function __construct(Collection $collection, Strategy $strategy)
    {
        $this->collection = $collection;
        $this->strategy = $strategy;
    }

    /**
     * @return Node
     *
     * @throws EmptyCollectionException
     */
    public function next(): Node
    {
        if ($this->collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes should not be empty');
        }

        $index = $this->strategy->getIndex($this->collection);

        return $this->collection[$index];
    }
}
