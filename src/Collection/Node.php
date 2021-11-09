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
    /**
     * @var array
     */
    private $info;

    public function __construct(string $name, int $weight = 0, array $info = [])
    {
        $this->name = $name;
        $this->weight = $weight;
        $this->info = $info;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getInfo(): array
    {
        return $this->info;
    }
}
