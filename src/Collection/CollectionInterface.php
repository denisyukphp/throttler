<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

interface CollectionInterface extends \Countable, \IteratorAggregate
{
    public function addNode(Node $node): self;

    public function getNode(int $index): Node;

    public function hasNode(Node $node): bool;

    public function removeNode(Node $node): void;

    public function purge(): void;

    public function isWeighted(): bool;

    public function isEmpty(): bool;

    /**
     * @return Node[]
     */
    public function toArray(): array;
}
