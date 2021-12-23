<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;

interface ThrottlerInterface
{
    public function pick(CollectionInterface $collection, array $context = []): Node;
}
