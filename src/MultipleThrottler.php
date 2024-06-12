<?php

declare(strict_types=1);

namespace Orangesoft\Throttler;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

final class MultipleThrottler implements ThrottlerInterface
{
    /**
     * @var array<string, ThrottlerInterface>
     */
    private array $throttlers = [];

    public function __construct(ThrottlerInterface ...$throttlers)
    {
        foreach ($throttlers as $throttler) {
            $this->throttlers[$throttler::class] = $throttler;
        }
    }

    /**
     * @param array<string, mixed> $context
     */
    public function pick(CollectionInterface $collection, array $context = []): NodeInterface
    {
        if (!isset($context['throttler']) || !\is_string($context['throttler'])) {
            throw new \RuntimeException('The required parameter "throttler" must be passed as a throttler\'s class name.'); // @codeCoverageIgnore
        }

        if (!isset($this->throttlers[$context['throttler']])) {
            throw new \RuntimeException(sprintf('The throttler "%s" is undefined.', $context['throttler'])); // @codeCoverageIgnore
        }

        if (!class_exists($context['throttler']) || !is_a($context['throttler'], ThrottlerInterface::class, true)) {
            throw new \UnexpectedValueException(sprintf('The throttler must be a class that exists and implements "%s" interface, "%s" given.', ThrottlerInterface::class, get_debug_type($context['throttler']))); // @codeCoverageIgnore
        }

        $throttler = $this->throttlers[$context['throttler']];

        return $throttler->pick($collection, $context);
    }
}
