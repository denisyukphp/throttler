<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\CounterInterface;

final class RoundRobinThrottler implements ThrottlerInterface
{
    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.');
        }

        $counter = $context['counter'] ?? spl_object_hash($collection);
        $key = $this->counter->next($counter) % \count($collection);

        return $collection->get($key);
    }
}
