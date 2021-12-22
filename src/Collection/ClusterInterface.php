<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection;

use Orangesoft\Throttler\ThrottlerInterface;

interface ClusterInterface
{
    public function balance(ThrottlerInterface $throttler, array $context = []): NodeInterface;
}
