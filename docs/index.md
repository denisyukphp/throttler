# Documentation

- [Configure Throttler](#configure-throttler)
- [Available strategies](#available-strategies)
- [Sort nodes](#sort-nodes)
- [Keep counter](#keep-counter)
- [Serialize strategies](#serialize-strategies)
- [Dynamically change strategy](#dynamically-change-strategy)
- [Balance cluster](#balance-cluster)

## Configure Throttler

You need to choose a strategy and configure it:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Throttler;

$throttler = new Throttler(
    new WeightedRoundRobinStrategy(
        new InMemoryCounter(start: 0)
    )
);
```

Collect a collection of nodes. Set weight for Node as the second argument if you are using weighted-strategies:

```php
$collection = new Collection([
    new Node('node1', 5),
    new Node('node2', 1),
    new Node('node3', 1),
]);
```

To get next Node call the `next()` method with passed collection:

```php
while (true) {
    /** @var Node $node */
    $node = $throttler->pick($collection);
    
    // ...
}
```

As a result, you will see the following distribution of nodes:

```text
+-------+
| node1 |
| node1 |
| node1 |
| node1 |
| node1 |
| node2 |
| node3 |
| etc.  |
+-------+
```

Result of Throttler depends on the chosen strategy.

## Available strategies

Strategies are divided into two types: random and round-robin. The following strategies are available:

- [RandomStrategy](../src/Strategy/RandomStrategy.php)
- [WeightedRandomStrategy](../src/Strategy/WeightedRandomStrategy.php)
- [FrequencyRandomStrategy](../src/Strategy/FrequencyRandomStrategy.php)
- [RoundRobinStrategy](../src/Strategy/RoundRobinStrategy.php)
- [WeightedRoundRobinStrategy](../src/Strategy/WeightedRoundRobinStrategy.php)
- [SmoothWeightedRoundRobinStrategy](../src/Strategy/SmoothWeightedRoundRobinStrategy.php)
- [MultipleDynamicStrategy](../src/Strategy/MultipleDynamicStrategy.php)
- [ClusterDetermineStrategy](../src/Strategy/ClusterDetermineStrategy.php)

## Sort nodes

For some strategies, such as [FrequencyRandomStrategy](../src/Strategy/FrequencyRandomStrategy.php), it might be necessary to adjust the order of nodes by their weight. This can be done with Sorter:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\Sorter;
use Orangesoft\Throttler\Collection\Desc;
use Orangesoft\Throttler\Strategy\FrequencyRandomStrategy;
use Orangesoft\Throttler\Throttler;

$collection = new Collection([
    new Node('node1',  32),
    new Node('node2',  16),
    new Node('node3',  512),
    new Node('node4',  1024),
    new Node('node5',  128),
    new Node('node6',  64),
    new Node('node7',  2048),
    new Node('node8',  256),
    new Node('node9',  8),
    new Node('node10', 4),
]);

$sorter = new Sorter();

$sorter->sort($collection, new Desc());
```

The nodes at the top of the list will be used more often. You can manage sorting using [Asc](../src/Collection/Asc.php) and [Desc](../src/Collection/Desc.php) comparators. Example for the Desc direction:

```text
+--------+--------+
| name   | weight |
+--------+--------+
| node7  | 2048   |
| node4  | 1024   |
| node3  | 512    |
| node8  | 256    |
| node5  | 128    |
| node6  | 64     |
| node1  | 32     |
| node2  | 16     |
| node9  | 8      |
| node10 | 4      |
+--------+--------+
```

FrequencyRandomStrategy has 2 not required options: frequency and depth. Frequency is probability to choose nodes from a first group in percent. Depth is length the first group from the list in percent. By default, frequency is 0.8 and depth is 0.2:

```php
$throttler = new Throttler(
    new FrequencyRandomStrategy(frequency: 0.8, depth: 0.2)
);

/** @var Node $node */
$node = $throttler->pick($collection);
```

The probability of choosing nodes for FrequencyRandomStrategy can be visualized as follows:

```text
+--------+--------+
| nodes  | chance |
+--------+--------+
| node7  | 40%    |
| node4  | 40%    |
+--------+--------+
| node3  | 2.5%   |
| node8  | 2.5%   |
| node5  | 2.5%   |
| node6  | 2.5%   |
| node1  | 2.5%   |
| node2  | 2.5%   |
| node9  | 2.5%   |
| node10 | 2.5%   |
+--------+--------+
```

If you need the reverse order of the nodes use Asc direction.

## Keep counter

For strategies are [RoundRobinStrategy](../src/Strategy/RoundRobinStrategy.php) and [WeightedRoundRobinStrategy](../src/Strategy/WeightedRoundRobinStrategy.php) you must use InMemoryCounter to remember order of nodes. A counter is not needed for round-robin strategies:

```php
<?php

use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\CounterInterface;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Predis\Client;

$strategy = new RoundRobinStrategy(
    new InMemoryCounter(start: 0)
);
```

You can replace InMemoryCounter if you need to keep the order of these strategies between PHP calls, for example, in queues. Just to implement [CounterInterface](../src/Strategy/CounterInterface.php):

```php
class RedisCounter implements CounterInterface
{
    public function __construct(
        private Client $client,
    ) {
    }

    public function next(string $name = 'default'): int
    {
        if (!$this->client->exists($name)) {
            $this->client->set($name, -1);
        }

        return $this->client->incr($name);
    }
}
```

In the example above, we wrote the counter with Redis.

```php
/** @var Predis\Client $client */

$strategy = new WeightedRoundRobinStrategy(
    new RedisCounter($client)
);
```

Now Throttler will resume work from the last node according to the chosen strategy.

## Serialize strategies

[SmoothWeightedRoundRobinStrategy](../src/Strategy/SmoothWeightedRoundRobinStrategy.php) does not support counters. Instead it you can serialize and unserialize this strategy to keep last state:

```php
<?php

use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;

$strategy = new SmoothWeightedRoundRobinStrategy();

/** @var string $serialized */
$serialized = serialize($strategy);
```

The serialization result will return an instance of SmoothWeightedRoundRobinStrategy with the actual weights for the nodes:

```php
/** @var SmoothWeightedRoundRobinStrategy $strategy */
$strategy = unserialize($serialized);
```

This way you can preserve the order of nodes for a given strategy between PHP calls.

## Dynamically change strategy

You can dynamically change the strategy from the client code. To do this, configure the [MultipleDynamicStrategy](../src/Strategy/MultipleDynamicStrategy.php) with the strategies you need:

```php
<?php

use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\Strategy\MultipleDynamicStrategy;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;

$throttler = new Throttler(
    new MultipleDynamicStrategy(
        new RoundRobinStrategy(new InMemoryCounter(start: 0)),
        new RandomStrategy(),
    )
);
```

Create collection of nodes:

```php
$collection = new Collection([
    new Node('node1'),
    new Node('node2'),
    new Node('node3'),
]);
```

Pass the `strategy_name` parameter through the context with the name of the strategy class according to which the collection needs to be balanced:

```php
/** @var Node $node */
$node = $throttler->pick($collection, [
    'strategy_name' => RoundRobinStrategy::class,
]);
```

The advantage of this method is that you do not need to create many instances of the balancer.

## Balance cluster

You can divide the nodes into clusters and set a specific balancing strategy for each cluster. To do this, configure the [ClusterDetermineStrategy](../src/Strategy/ClusterDetermineStrategy.php) as shown below:

```php
<?php

use Orangesoft\Throttler\Throttler;
use Orangesoft\Throttler\Strategy\ClusterSet;
use Orangesoft\Throttler\Strategy\ClusterDetermineStrategy;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;

$throttler = new Throttler(
    new ClusterDetermineStrategy(
        new ClusterSet(new RoundRobinStrategy(new InMemoryCounter(start: 0)), ['cluster1']),
        new ClusterSet(new RandomStrategy(), ['cluster2', 'cluster3']),
    )
);
```

Create clusters from nodes:

```php
$collection = new Collection([
    new Node('node1'),
    new Node('node2'),
    new Node('node3'),
]);

$cluster = new Cluster('cluster1', $collection);
```

Using the `balance()` method, force your cluster to balance:

```php
/** @var Node $node */
$node = $cluster->balance($throttler);
```

This method is well suited in cases where the nodes can be divided according to a specific criterion and each cluster needs its own balancing strategy.
