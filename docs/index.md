# Documentation

- [Configuration](#configuration)
- [Available strategies](#available-strategies)
- [Sort nodes](#sort-nodes)
- [Keep counter](#keep-counter)
- [Serialize strategies](#serialize-strategies)
- [Supported tools](#supported-tools)

## Configuration

You need to collect a collection of nodes and choose a strategy. Set weight for Node as the second argument in constructor if you are using weighted-strategies:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Throttler;

$nodes = [
    new Node('node1', 5),
    new Node('node2', 1),
    new Node('node3', 1),
];

$collection = new Collection($nodes);

$strategy = new WeightedRoundRobinStrategy(
    new InMemoryCounter()
);

$throttler = new Throttler($collection, $strategy);
```

To use Throttler just call the `next()` method.

```php
while (true) {
    /** @var Node $node */
    $node = $throttler->next();
    
    $name = $node->getName();
    
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

Thtottler's result depends on the chosen strategy.

## Available strategies

Strategies are divided into two types: random and round-robin. The following strategies are available:

- [RandomStrategy](../src/Strategy/RandomStrategy.php)
- [WeightedRandomStrategy](../src/Strategy/WeightedRandomStrategy.php)
- [FrequencyRandomStrategy](../src/Strategy/FrequencyRandomStrategy.php)
- [RoundRobinStrategy](../src/Strategy/RoundRobinStrategy.php)
- [WeightedRoundRobinStrategy](../src/Strategy/WeightedRoundRobinStrategy.php)
- [SmoothWeightedRoundRobinStrategy](../src/Strategy/SmoothWeightedRoundRobinStrategy.php)

## Sort nodes

For some strategies, such as [FrequencyRandomStrategy](../src/Strategy/FrequencyRandomStrategy.php), it may be necessary to adjust the order of nodes by their weight. This can be done with Sorter:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
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

/** @var Collection $sortedCollection */
$sortedCollection = $sorter->sort($collection, new Desc());
```

The nodes at the top of the list should be used more often. You can manage sorting using [Asc](../src/Collection/Asc.php) and [Desc](../src/Collection/Desc.php) comparators. Example for the Desc direction:

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

FrequencyRandomStrategy has 2 not required options: frequency is probability to choose nodes from a first group in percent, depth is length the first group from the list in percent. By default frequency is 80 and depth is 20.

```php
$frequency = 80;
$depth = 20;

$strategy = new FrequencyRandomStrategy($frequency, $depth);

$throttler = new Throttler($sortedCollection, $strategy);

/** @var Node $node */
$node = $throttler->next();
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

For strategies are [RoundRobinStrategy](../src/Strategy/RoundRobinStrategy.php) and [WeightedRoundRobinStrategy](../src/Strategy/WeightedRoundRobinStrategy.php) you must use InMemoryCounter to remember order of nodes. A counter is not needed for round-robin strategies.

```php
<?php

use Orangesoft\Throttler\Strategy\Counter;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Predis\Client;

$counter = new InMemoryCounter();

$strategy = new RoundRobinStrategy($counter);
```

You can replace InMemoryCounter if you need to keep the order of these strategies between PHP calls, for example, in queues. Just implement [Counter](../src/Strategy/Counter.php) interface:

```php
class RedisCounter implements Counter
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function increment(string $key = 'throttler:weighted-round-robin:counter'): int
    {
        if (!$this->client->exists($key)) {
            $this->client->set($key, -1);
        }

        return $this->client->incr($key);
    }
}
```

In the example above, we wrote the counter with Redis.

```php
/** @var Predis\Client $client */

$counter = new RedisCounter($client);

$strategy = new WeightedRoundRobinStrategy($counter);
```

Now Throttler will resume work from the last node according to the chosen strategy.

## Serialize strategies

[SmoothWeightedRoundRobinStrategy](../src/Strategy/SmoothWeightedRoundRobinStrategy.php) does not supported counters. Instead it you can serialize and unserialize this strategy to keep last state:

```php
<?php

use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;

$strategy = new SmoothWeightedRoundRobinStrategy();

/** @var string $serialized */
$serialized = serialize($strategy);
```

The serialization result will return an instance of SmoothWeightedRoundRobinStrategy with the actual weights for the nodes.

```php
/** @var SmoothWeightedRoundRobinStrategy $strategy */
$strategy = unserialize($serialized);
```

This way you can preserve the order of nodes for a given strategy between PHP calls.

## Supported tools

The table below shows which tools each strategy supports:

```text
+----------------------------------+-----------+-----------+-----------+
|                                  | Sort      | Counter   | Serialize |
+----------------------------------+-----------+-----------+-----------+
| RandomStrategy                   | No        | No        | No        |
| WeightedRandomStrategy           | No        | No        | No        |
| FrequencyRandomStrategy          | Yes       | No        | No        |
| RoundRobinStrategy               | No        | Yes       | No        |
| WeightedRoundRobinStrategy       | No        | Yes       | No        |
| SmoothWeightedRoundRobinStrategy | No        | No        | Yes       |
+----------------------------------+-----------+-----------+-----------+
```

To implement your strategy in Throttler, you need to implement [Strategy](../src/Strategy/Strategy.php) interface.
