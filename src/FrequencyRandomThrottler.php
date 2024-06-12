<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

final class FrequencyRandomThrottler implements ThrottlerInterface
{
    public function __construct(
        private float $frequency = 0.8,
        private float $threshold = 0.2,
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

        $total = \count($collection);
        $lowerKey = (int) ceil($this->threshold * $total);
        $higherKey = $lowerKey + (1 < $total ? 1 : 0);
        $probability = mt_rand() / mt_getrandmax();
        $key = $this->frequency >= $probability ? mt_rand(1, $lowerKey) : mt_rand($higherKey, $total);

        return $collection->get($key - 1);
    }
}
