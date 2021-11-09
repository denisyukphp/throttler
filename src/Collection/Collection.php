<?php

namespace Orangesoft\Throttler\Collection;

final class Collection implements CollectionInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $splObjectStorage;
    /**
     * @var bool
     */
    private $isWeighted = true;

    /**
     * @param iterable|NodeInterface[] $nodes
     */
    public function __construct(iterable $nodes = [])
    {
        $this->splObjectStorage = new \SplObjectStorage();

        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * @param int $index
     *
     * @return object|NodeInterface
     *
     * @throws \OutOfRangeException
     */
    public function getNode(int $index): NodeInterface
    {
        if (!$this->isValidIndex($index)) {
            throw new \OutOfRangeException(
                sprintf('Undefined index %d', $index)
            );
        }

        $this->splObjectStorage->rewind();

        while ($index--) {
            $this->splObjectStorage->next();
        }

        return $this->splObjectStorage->current();
    }

    private function isValidIndex(int $index): bool
    {
        return $index < $this->getQuantity();
    }

    public function addNode(NodeInterface $node): void
    {
        if (0 === $node->getWeight()) {
            $this->isWeighted = false;
        }

        $this->splObjectStorage->attach($node);
    }

    public function hasNode(NodeInterface $node): bool
    {
        return $this->splObjectStorage->contains($node);
    }

    public function removeNode(NodeInterface $node): void
    {
        $this->splObjectStorage->detach($node);
    }

    public function isWeighted(): bool
    {
        return $this->isWeighted;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->getQuantity();
    }

    public function getQuantity(): int
    {
        return $this->splObjectStorage->count();
    }

    /**
     * @return NodeInterface[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this->splObjectStorage, false);
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function offsetExists($index): bool
    {
        return $this->isValidIndex($index);
    }

    /**
     * @param int $index
     *
     * @return NodeInterface
     */
    public function offsetGet($index): NodeInterface
    {
        return $this->getNode($index);
    }

    /**
     * @param null $index
     * @param NodeInterface $node
     *
     * @throws \OutOfBoundsException
     */
    public function offsetSet($index, $node): void
    {
        if (null !== $index) {
            throw new \OutOfBoundsException('Index cannot be set at all');
        }

        $this->addNode($node);
    }

    /**
     * @param int $index
     */
    public function offsetUnset($index): void
    {
        $node = $this->getNode($index);

        $this->removeNode($node);
    }

    public function count(): int
    {
        return $this->getQuantity();
    }

    public function serialize(): string
    {
        return serialize($this->splObjectStorage);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->splObjectStorage = unserialize($serialized, [
            'allowed_classes' => [
                \SplObjectStorage::class,
                Node::class,
            ],
        ]);
    }

    /**
     * @return \ArrayIterator|NodeInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        $nodes = $this->toArray();

        return new \ArrayIterator($nodes);
    }
}
