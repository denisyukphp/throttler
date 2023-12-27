<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

interface CollectionInterface extends \Countable, \IteratorAggregate
{
    public function add(NodeInterface $node): self;

    public function get(int $key): NodeInterface;

    public function has(NodeInterface $node): bool;

    public function remove(NodeInterface $node): self;

    public function sort(callable $callback): self;

    public function isEmpty(): bool;

    /**
     * @return NodeInterface[]
     */
    public function toArray(): array;
}
