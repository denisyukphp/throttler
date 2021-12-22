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

    public function addNode(NodeInterface $node): self
    {
        if (0 >= $node->getWeight()) {
            $this->isWeighted = false;
        }

        $this->storage->attach($node);

        return $this;
    }

    public function getNode(int $index): NodeInterface
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

        /** @var NodeInterface $node */
        $node = $this->storage->current();

        return $node;
    }

    public function hasNode(NodeInterface $node): bool
    {
        return $this->storage->contains($node);
    }

    public function removeNode(NodeInterface $node): void
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
     * @return NodeInterface[]
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
