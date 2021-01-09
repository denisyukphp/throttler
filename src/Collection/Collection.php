<?php

namespace Orangesoft\Throttler\Collection;

class Collection implements \ArrayAccess, \Countable, \Serializable, \IteratorAggregate
{
    /**
     * @var Node[]
     */
    protected $nodes = [];

    /**
     * @param iterable|Node[] $nodes
     */
    public function __construct(iterable $nodes = [])
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * @param Node $node
     *
     * @return self
     */
    public function addNode(Node $node): self
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function offsetExists($index): bool
    {
        return isset($this->nodes[$index]);
    }

    /**
     * @param int $index
     *
     * @return Node
     *
     * @throws \OutOfRangeException
     */
    public function offsetGet($index): Node
    {
        if (!$this->offsetExists($index)) {
            throw new \OutOfRangeException(
                sprintf('Undefined index %d', $index)
            );
        }

        return $this->nodes[$index];
    }

    /**
     * @param int|null $index
     * @param Node $node
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function offsetSet($index, $node): void
    {
        if (!$node instanceof Node) {
            throw new \InvalidArgumentException(
                sprintf('Node must be of type %s', Node::class)
            );
        }

        if (is_int($index)) {
            $this->nodes[$index] = $node;
        } elseif (is_null($index)) {
            $this->nodes[] = $node;
        } else {
            throw new \OutOfBoundsException('Index must be integer or null');
        }
    }

    /**
     * @param int $index
     */
    public function offsetUnset($index): void
    {
        unset($this->nodes[$index]);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return 0 === $this->getQuantity();
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->count();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->nodes);
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->nodes);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->nodes = unserialize($serialized, [
            'allowed_classes' => [
                Node::class,
            ],
        ]);
    }

    /**
     * @return \ArrayIterator|Node[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->nodes);
    }
}
