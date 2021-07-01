<?php

namespace Orangesoft\Throttler\Collection;

interface CollectionInterface extends \ArrayAccess, \Countable, \Serializable, \IteratorAggregate
{
    public function getNode(int $index): NodeInterface;

    public function addNode(NodeInterface $node): void;

    public function hasNode(NodeInterface $node): bool;

    public function removeNode(NodeInterface $node): void;

    public function isWeighted(): bool;

    public function isEmpty(): bool;

    public function getQuantity(): int;

    /**
     * @return NodeInterface[]
     */
    public function toArray(): array;
}
