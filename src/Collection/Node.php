<?php

namespace Orangesoft\Throttler\Collection;

class Node
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $weight;

    /**
     * @param string $name
     * @param int $weight
     */
    public function __construct(string $name, int $weight = 0)
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }
}
