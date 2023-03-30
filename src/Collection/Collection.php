<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Collection implements CollectionInterface
{
    private \SplObjectStorage $nodes;
    private bool $isWeighted = true;

    public function __construct(iterable $nodes = [])
    {
        $this->nodes = new \SplObjectStorage();

        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    public function addNode(Node $node): self
    {
        if (0 >= $node->weight) {
            $this->isWeighted = false;
        }

        $this->nodes->attach($node);

        return $this;
    }

    public function getNode(int $index): Node
    {
        if ($index > $this->nodes->count()) {
            throw new \InvalidArgumentException(sprintf('Cannot find node at index "%d".', $index));
        }

        $this->nodes->rewind();

        while ($index--) {
            $this->nodes->next();
        }

        /** @var Node $node */
        $node = $this->nodes->current();

        return $node;
    }

    public function hasNode(Node $node): bool
    {
        return $this->nodes->contains($node);
    }

    public function removeNode(Node $node): void
    {
        $this->nodes->detach($node);
    }

    public function purge(): void
    {
        $this->nodes->removeAll($this->nodes);
    }

    public function isWeighted(): bool
    {
        return $this->isWeighted;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->nodes->count();
    }

    public function count(): int
    {
        return $this->nodes->count();
    }

    /**
     * @return Node[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this->nodes, false);
    }

    public function getIterator(): \Traversable
    {
        return $this->nodes;
    }
}
