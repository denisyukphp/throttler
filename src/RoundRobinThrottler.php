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

    /**
     * @param array<string, mixed> $context
     */
    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.'); // @codeCoverageIgnore
        }

        if (isset($context['counter']) && !\is_string($context['counter'])) {
            throw new \RuntimeException(sprintf('The parameter "counter" must be as a string, %s given.', get_debug_type($context['counter']))); // @codeCoverageIgnore
        }

        $counter = $context['counter'] ?? spl_object_hash($collection);
        $key = $this->counter->next($counter) % \count($collection);

        return $collection->get($key);
    }
}
