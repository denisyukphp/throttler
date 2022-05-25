<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Strategy;

use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Node;

final class MultipleDynamicStrategy implements StrategyInterface
{
    /**
     * @var array<string, StrategyInterface>
     */
    private array $pool = [];

    public function __construct(StrategyInterface ...$strategies)
    {
        foreach ($strategies as $strategy) {
            $this->pool[$strategy::class] = $strategy;
        }
    }

    public function getNode(CollectionInterface $collection, array $context = []): Node
    {
        if (!isset($context['strategy_name'])) {
            throw new \LogicException('Required parameter "strategy_name" is missing.');
        }

        if (!class_exists($context['strategy_name']) || !is_a($context['strategy_name'], StrategyInterface::class, true)) {
            throw new \LogicException(
                vsprintf('Strategy must be a class that exists and implements "%s" interface, "%s" given.', [
                    StrategyInterface::class,
                    $context['strategy_name'],
                ])
            );
        }

        if (!isset($this->pool[$context['strategy_name']])) {
            throw new \LogicException(
                sprintf('Strategy "%s" is undefined.', $context['strategy_name'])
            );
        }

        /** @var StrategyInterface $strategy */
        $strategy = $this->pool[$context['strategy_name']];

        return $strategy->getNode($collection, $context);
    }
}
