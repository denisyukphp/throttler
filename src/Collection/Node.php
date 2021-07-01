<?php

namespace Orangesoft\Throttler\Collection;

final class Node implements NodeInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $weight;

    public function __construct(string $name, int $weight = 0)
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
