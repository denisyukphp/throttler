<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class InMemoryCollection implements CollectionInterface
{
    /**
     * @var array<int, NodeInterface>
     */
    private array $nodes;
    /**
     * @var array<string, int>
     */
    private array $keys;

    /**
     * @param NodeInterface[] $nodes
     */
    public function __construct(array $nodes = [])
    {
        $this->nodes = [];
        $this->keys = [];

        foreach ($nodes as $node) {
            if ($this->has($node)) {
                throw new \InvalidArgumentException(sprintf('All nodes must be unique, "%s" given as duplicate.', $node->getName()));
            }

            $this->nodes[] = $node;
            $this->keys[$node->getName()] = array_key_last($this->nodes);
        }
    }

    public function add(NodeInterface $node): self
    {
        if ($this->has($node)) {
            throw new \UnexpectedValueException(sprintf('The node "%s" has been already added.', $node->getName()));
        }

        $self = clone $this;
        $self->nodes[] = $node;
        $self->keys[$node->getName()] = array_key_last($self->nodes);

        return $self;
    }

    public function get(int $key): NodeInterface
    {
        if (!isset($this->nodes[$key])) {
            throw new \OutOfRangeException(sprintf('Can\'t get node at key "%d".', $key));
        }

        return $this->nodes[$key];
    }

    public function has(NodeInterface $node): bool
    {
        return \array_key_exists($node->getName(), $this->keys);
    }

    public function remove(NodeInterface $node): self
    {
        if (!$this->has($node)) {
            throw new \UnexpectedValueException(sprintf('The node "%s" hasn\'t been already added.', $node->getName()));
        }

        $self = clone $this;
        unset($self->nodes[$self->keys[$node->getName()]]);

        return new self($self->toArray());
    }

    public function sort(callable $callback): self
    {
        $nodes = $this->toArray();
        usort($nodes, $callback);

        return new self($nodes);
    }

    public function isEmpty(): bool
    {
        return 0 == $this->count();
    }

    public function count(): int
    {
        return \count($this->nodes);
    }

    /**
     * @return NodeInterface[]
     */
    public function toArray(): array
    {
        return $this->nodes;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->nodes);
    }
}
