<?php

namespace Orangesoft\Throttler\Collection;

interface NodeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getWeight(): int;
}
