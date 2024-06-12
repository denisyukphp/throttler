<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

/**
 * @template T of NodeInterface
 * @template-extends \IteratorAggregate<int, T>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate
{
    public function add(NodeInterface $node): self;

    public function get(int $key): NodeInterface;

    public function has(NodeInterface $node): bool;

    public function remove(NodeInterface $node): self;

    /**
     * @param callable(T, T): int $callback
     */
    public function sort(callable $callback): self;

    public function isEmpty(): bool;

    /**
     * @return array<int, T>
     */
    public function toArray(): array;
}
