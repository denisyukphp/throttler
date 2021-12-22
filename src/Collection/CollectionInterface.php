<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

interface CollectionInterface extends \Countable, \IteratorAggregate
{
    public function addNode(NodeInterface $node): self;

    public function getNode(int $index): NodeInterface;

    public function hasNode(NodeInterface $node): bool;

    public function removeNode(NodeInterface $node): void;

    public function purge(): void;

    public function isWeighted(): bool;

    public function isEmpty(): bool;

    /**
     * @return NodeInterface[]
     */
    public function toArray(): array;
}
