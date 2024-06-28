<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Sort\Desc;

final class FrequencyRandomThrottler implements ThrottlerInterface
{
    public function __construct(
        private float $threshold = 0.2,
        private float $frequency = 0.8,
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

        $sorted = $collection->sort(new Desc());
        $total = \count($sorted);
        $lowerKey = (int) ceil($this->threshold * $total);
        $higherKey = $lowerKey + (1 < $total ? 1 : 0);
        $probability = mt_rand() / mt_getrandmax();
        $key = $this->frequency >= $probability ? mt_rand(1, $lowerKey) : mt_rand($higherKey, $total);

        return $sorted->get($key - 1);
    }
}
