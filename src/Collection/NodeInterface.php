<?php

namespace Orangesoft\Throttler\Collection;

interface NodeInterface
{
    public function getName(): string;

    public function getWeight(): int;
}
