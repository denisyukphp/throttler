<?php

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Strategy\StrategyInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Exception\EmptyCollectionException;

final class Throttler implements ThrottlerInterface
{
    /**
     * @var CollectionInterface
     */
    private $collection;
    /**
     * @var StrategyInterface
     */
    private $strategy;

    public function __construct(CollectionInterface $collection, StrategyInterface $strategy)
    {
        $this->collection = $collection;
        $this->strategy = $strategy;
    }

    public function next(): NodeInterface
    {
        if ($this->collection->isEmpty()) {
            throw new EmptyCollectionException('Collection of nodes must not be empty');
        }

        $index = $this->strategy->getIndex($this->collection);

        return $this->collection->getNode($index);
    }
}
