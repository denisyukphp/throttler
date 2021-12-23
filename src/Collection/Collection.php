<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

final class Collection implements CollectionInterface
{
    private \SplObjectStorage $storage;

    private bool $isWeighted = true;

    public function __construct(iterable $nodes = [])
    {
        $this->storage = new \SplObjectStorage();

        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    public function addNode(Node $node): self
    {
        if (0 >= $node->weight) {
            $this->isWeighted = false;
        }

        $this->storage->attach($node);

        return $this;
    }

    public function getNode(int $index): Node
    {
        if ($index > $this->storage->count()) {
            throw new \InvalidArgumentException(
                sprintf('Cannot find node at index %d', $index)
            );
        }

        $this->storage->rewind();

        while ($index--) {
            $this->storage->next();
        }

        /** @var Node $node */
        $node = $this->storage->current();

        return $node;
    }

    public function hasNode(Node $node): bool
    {
        return $this->storage->contains($node);
    }

    public function removeNode(Node $node): void
    {
        $this->storage->detach($node);
    }

    public function purge(): void
    {
        $this->storage->removeAll($this->storage);
    }

    public function isWeighted(): bool
    {
        return $this->isWeighted;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->storage->count();
    }

    public function count(): int
    {
        return $this->storage->count();
    }

    /**
     * @return Node[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this->storage, false);
    }

    public function getIterator(): \Traversable
    {
        return $this->storage;
    }
}
