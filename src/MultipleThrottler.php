<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

final class MultipleThrottler implements ThrottlerInterface
{
    private array $throttlers = [];

    public function __construct(ThrottlerInterface ...$throttlers)
    {
        foreach ($throttlers as $throttler) {
            $this->throttlers[$throttler::class] = $throttler;
        }
    }

    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if (!isset($context['throttler'])) {
            throw new \RuntimeException('Required parameter "throttler" is missing.');
        }

        if (!isset($this->throttlers[$context['throttler']])) {
            throw new \RuntimeException(sprintf('Throttler "%s" is undefined.', $context['throttler']));
        }

        if (!class_exists($context['throttler']) || !is_a($context['throttler'], ThrottlerInterface::class, true)) {
            throw new \UnexpectedValueException(sprintf('Throttler must be a class that exists and implements "%s" interface, "%s" given.', ThrottlerInterface::class, get_debug_type($context['throttler'])));
        }

        /** @var ThrottlerInterface $throttler */
        $throttler = $this->throttlers[$context['throttler']];

        return $throttler->pick($collection, $context);
    }
}
