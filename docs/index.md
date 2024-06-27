# Documentation

- [Available strategies](#available-strategies)
  - [Random](#random)
  - [Weighted random](#weighted-random)
  - [Frequency random](#frequency-random)
  - [Round-robin](#round-robin)
  - [Weighted round-robin](#weighted-round-robin)
  - [Smooth weighted round-robin](#smooth-weighted-round-robin)
- [Keep states](#keep-states)
  - [Use counting](#use-counting)
  - [Use serialization](#use-serialization)
- [Custom counter](#custom-counter)
- [Custom strategy](#custom-strategy)
- [Multiple throttler](#multiple-throttler)
- [Balance cluster](#balance-cluster)
- [Guzzle middleware](#guzzle-middleware)

## Available strategies

The following strategies are available:

### Random

Random is a strategy where each node has an equal probability of being chosen, regardless of previous selections or the order of nodes. Use [Orangesoft\Throttler\RandomThrottler](../src/RandomThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\RandomThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new RandomThrottler();

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the random strategy's output:

```text
+---------+-------------+--------+
| request | node        | chance |
+---------+-------------+--------+
|       1 | 192.168.0.1 |  25.0% |
|       2 | 192.168.0.2 |  25.0% |
|       3 | 192.168.0.3 |  25.0% |
|       4 | 192.168.0.4 |  25.0% |
|       n | etc.        |        |
+---------+-------------+--------+
```

### Weighted random

Weighted random is a sort of random strategy where the probability of selecting each node is proportional to its assigned weight, allowing some nodes to have a higher chance of being chosen than others. Use [Orangesoft\Throttler\WeightedRandomThrottler](../src/WeightedRandomThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\WeightedRandomThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new WeightedRandomThrottler();

$collection = new InMemoryCollection([
    new Node('192.168.0.1', 5),
    new Node('192.168.0.2', 1),
    new Node('192.168.0.3', 1),
    new Node('192.168.0.4', 1),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the weighted random strategy's output:

```text
+---------+-------------+--------+
| request | node        | chance |
+---------+-------------+--------+
|       1 | 192.168.0.1 |  62.5% |
|       2 | 192.168.0.2 |  12.5% |
|       3 | 192.168.0.3 |  12.5% |
|       4 | 192.168.0.4 |  12.5% |
|       n | etc.        |        |
+---------+-------------+--------+
```

### Frequency random

Frequency random is a strategy that allows selecting nodes with a specific frequency for a certain depth of the collection. For example, a threshold of `0.2` represents 20% of the nodes from their total length, and a frequency of `0.8` means there's an 80% probability that the first 20% of nodes will be picked. Nodes are sorted by their weight or provided in the order they were added to the collection. Use [Orangesoft\Throttler\FrequencyRandomThrottler](../src/FrequencyRandomThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\FrequencyRandomThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new FrequencyRandomThrottler(
    threshold: 0.2,
    frequency: 0.8,
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1',  4),
    new Node('192.168.0.2',  8),
    new Node('192.168.0.3',  16),
    new Node('192.168.0.4',  32),
    new Node('192.168.0.5',  64),
    new Node('192.168.0.6',  128),
    new Node('192.168.0.7',  256),
    new Node('192.168.0.8',  512),
    new Node('192.168.0.9',  1024),
    new Node('192.168.0.10', 2048),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the frequency random strategy's output:

```text
+----------+--------------+--------+
| request  | node         | chance |
+----------+--------------+--------+
|        1 | 192.168.0.10 |  40.0% |
|        2 | 192.168.0.9  |  40.9% |
+----------+--------------+--------+
|        3 | 192.168.0.8  |  2.5%  |
|        4 | 192.168.0.7  |  2.5%  |
|        5 | 192.168.0.6  |  2.5%  |
|        6 | 192.168.0.5  |  2.5%  |
|        7 | 192.168.0.4  |  2.5%  |
|        8 | 192.168.0.3  |  2.5%  |
|        9 | 192.168.0.2  |  2.5%  |
|       10 | 192.168.0.1  |  2.5%  |
|        n | etc.         |        |
+----------+--------------+--------+
```

### Round-robin

Round-robin is a strategy in which nodes in a collection are processed cyclically and sequentially, with equal priority. Use [Orangesoft\Throttler\RoundRobinThrottler](../src/RoundRobinThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new RoundRobinThrottler(
    new InMemoryCounter(),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the round-robin strategy's output:

```text
+---------+-------------+
| request | node        |
+---------+-------------+
|       1 | 192.168.0.1 |
|       2 | 192.168.0.2 |
|       3 | 192.168.0.3 |
|       4 | 192.168.0.4 |
|       n | etc.        |
+---------+-------------+
```

### Weighted round-robin

Weighted round-robin is a modification of the round-robin strategy, where each node is assigned a weight that determines its priority or frequency of selection in the distribution cycle. Use [Orangesoft\Throttler\WeightedRoundRobinThrottler](../src/WeightedRoundRobinThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\WeightedRoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new WeightedRoundRobinThrottler(
    new InMemoryCounter(),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1', 5),
    new Node('192.168.0.2', 1),
    new Node('192.168.0.3', 1),
    new Node('192.168.0.4', 1),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the weighted round-robin strategy's output:

```text
+---------+-------------+
| request | node        |
+---------+-------------+
|       1 | 192.168.0.1 |
|       2 | 192.168.0.1 |
|       3 | 192.168.0.1 |
|       4 | 192.168.0.1 |
|       5 | 192.168.0.1 |
|       6 | 192.168.0.2 |
|       7 | 192.168.0.3 |
|       8 | 192.168.0.4 |
|       n | etc.        |
+---------+-------------+
```

### Smooth weighted round-robin

Smooth weighted round-robin is an improved version of weighted round-robin that provides a more even distribution of load among nodes with different weights, minimizing fluctuations in the selection of elements. Use [Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler](../src/SmoothWeightedRoundRobinThrottler.php) as shown below:

```php
<?php

use Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new SmoothWeightedRoundRobinThrottler();

$collection = new InMemoryCollection([
    new Node('192.168.0.1', 5),
    new Node('192.168.0.2', 1),
    new Node('192.168.0.3', 1),
    new Node('192.168.0.4', 1),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

See a visualization of the smooth weighted round-robin strategy's output:

```text
+---------+-------------+
| request | node        |
+---------+-------------+
|       1 | 192.168.0.1 |
|       2 | 192.168.0.1 |
|       3 | 192.168.0.2 |
|       4 | 192.168.0.1 |
|       5 | 192.168.0.3 |
|       6 | 192.168.0.1 |
|       7 | 192.168.0.4 |
|       8 | 192.168.0.1 |
|       n | etc.        |
+---------+-------------+
```

## Keep states

Load balancing strategies can be of 2 types: *random-based* and *round-robin based*. Random-based strategies don't support keeping states between calls in different processes, as each request is based on probability. Round-robin based strategies support keeping states through a counting or serialization:

```text
+-----------------------------+---------------+
| strategy                    | method        |
+-----------------------------+---------------+
| random                      | [x]           |
| weighted random             | [x]           |
| frequency random            | [x]           |
| round-robin                 | counting      |
| weighted round-robin        | counting      |
| smooth weighted round-robin | serialization |
+-----------------------------+---------------+
```

This is especially useful when it's necessary to resume work precisely from where the previous process ended.

### Use counting

For round-robin and weighted round-robin strategies, the counter `Orangesoft\Throttler\Counter\InMemoryCounter::class` is available, which stores the request count in memory:

```php
<?php

use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\InMemoryCollection;

$counter = 0;

$throttler = new RoundRobinThrottler(
    new InMemoryCounter(
        start: $counter,
    ),
)

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...

    $counter++;
}
```

You can save the current request count in any storage and resume work from the last iteration as shown below:

```php
<?php

use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;

$counter = 1_000_000;

$throttler = new RoundRobinThrottler(
    new InMemoryCounter(
        start: $counter,
    ),
);
```

It's worth noting that you can also implement your own counter using Redis or another in-memory storage by implementing the `Orangesoft\Throttler\Counter\CounterInterface::next(string $name = 'default'): int` interface. This approach allows you to encapsulate all the logic for saving the request count in one place.

### Use serialization

To keep state for smooth weighted round-robin strategy you should serialize the whole object `Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler::class` using the `serialize(mixed $value): string` function as shown below:

```php
<?php

use Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new SmoothWeightedRoundRobinThrottler();

$collection = new InMemoryCollection([
    new Node('192.168.0.1', 5),
    new Node('192.168.0.2', 1),
    new Node('192.168.0.3', 1),
    new Node('192.168.0.4', 1),
]);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}

/** @var string $serialized */
$serialized = serialize($throttler);
```

You can save the serialization result in any storage and restore the strategy's operation using the `unserialize(string $data, array $options = []): mixed` function. The serialization result will return an instance of `Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler::class` with the actual weights for the nodes:

```php
/** @var SmoothWeightedRoundRobinThrottler $throttler */
$throttler = unserialize($serialized);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

This way keep state the order of nodes for a given strategy between PHP calls.

## Custom counter

To create a custom counter for *round-robin based* strategies, for example, using Redis, you need to implement the `Orangesoft\Throttler\Counter\CounterInterface::next(string $name = 'default'): int` interface as shown below:

```php
<?php

use Orangesoft\Throttler\Counter\CounterInterface;

$counter = new class implements CounterInterface
{
    public function next(string $name = 'default') : int
    {
        // ...
    }
};
```

The main task of the counter is to maintain the order of called nodes for *round-robin based* strategies.

## Custom strategy

To create a custom strategy with your own load balancing logic, you need to implement the `Orangesoft\Throttler\ThrottlerInterface::pick(Orangesoft\Throttler\Collection\CollectionInterface $collection, array $context = []): Orangesoft\Throttler\Collection\NodeInterface` interface. The main idea is to pick a specific node from the collection and return it:

```php
<?php

use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\Counter\CounterInterface;
use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new class implements ThrottlerInterface
{
    public function __construct(
        private CounterInterface $counter,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function pick(CollectionInterface $collection, array $context = []) : NodeInterface
    {
        if ($collection->isEmpty()) {
            throw new \RuntimeException('Collection of nodes mustn\'t be empty.');
        }
    
        // ...
    }
};
```

With your own strategies, you can wrap existing ones and, for example, cache their behavior.

## Multiple throttler

To dynamically change strategies from client code, use `Orangesoft\Throttler\MultipleThrottler::class` after pre-configuring it with preferred strategies:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\MultipleThrottler;
use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\RandomThrottler;

$throttler = new MultipleThrottler(
    new RoundRobinThrottler(new InMemoryCounter()),
    new RandomThrottler(),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);
```
To call a specific strategy, you need to pass the strategy class name through the required context parameter `throttler` and the optional parameter `counter` into the strategy context `Orangesoft\Throttler\ThrottlerInterface::pick(Orangesoft\Throttler\Collection\CollectionInterface $collection, array $context = []): Orangesoft\Throttler\Collection\NodeInterface` as shown below:

```php
/** @var NodeInterface $node */
$node = $throttler->pick(
    collection: $collection,
    context: [
        'throttler' => RoundRobinStrategy::class,
        'counter' => InMemoryCounter::class,
    ],
);
```

The context parameter `throttler` specifies the class of the strategy to be accessed, while `counter` sets the name for the counter, which will be passed to the `Orangesoft\Throttler\Counter\CounterInterface::next(string $name = 'default'): int` method to avoid conflicts between strategies.

## Balance cluster

You can add specific node collections to clusters and run the load balancer only for a specific cluster. Configure `Orangesoft\Throttler\Cluster\ClusterPool::class`, where you need to bind the desired strategies to the cluster name, and create the required number of clusters `Orangesoft\Throttler\Cluster\Cluster::class`:

```php
<?php

use Orangesoft\Throttler\Cluster\Cluster;
use Orangesoft\Throttler\Cluster\ClusterPool;
use Orangesoft\Throttler\Cluster\ClusterSet;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Throttler\RandomThrottler;
use Orangesoft\Throttler\Throttler\RoundRobinThrottler;

$pool = new ClusterPool(
    new ClusterSet(new RoundRobinThrottler(new InMemoryCounter()), ['Mercury']),
    new ClusterSet(new RandomThrottler(), ['Venus', 'Earth']),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

$cluster = new Cluster('Mercury', $collection);
```

From the example above, the cluster of nodes named `Mercury` will work according to the `Orangesoft\Throttler\Throttler\RoundRobinThrottler::class` strategy. To balance nodes from the cluster, call the `Orangesoft\Throttler\Cluster\ClusterInterface::balance(Orangesoft\Throttler\ThrottlerInterface $pool, array $context = []): Orangesoft\Throttler\Collection\NodeInterface` method:

```php
/** @var NodeInterface $node */
$node = $cluster->balance(
    pool: $pool,
    context: [
        'counter' => 'Mercury',
    ],
);
```

Note that you can also pass an optional context parameter `counter` with the counter name to avoid conflicts between clusters that use *round-robin based* strategies.

## Guzzle middleware

Let's break down an example of how to configure Guzzle for proxy balancing using middleware, which allows hiding a real IP server. To install the necessary packages to demonstrate proxy balancing in Guzzle, let's use the [Composer](https://getcomposer.org/) package manager:

```text
composer require \
    && orangesoft/throttler \
    && guzzlehttp/guzzle \
    && psr/http-message \
    && predis/predis
```

The package [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) is necessary for HTTP requests, [psr/http-message](https://github.com/php-fig/http-message) — HTTP message interfaces, [predis/predis](https://github.com/predis/predis) — for saving balancing strategies between the callings of PHP processes.

Write proxy middleware for Guzzle that will add the proxy to every HTTP-requests according to the chosen strategy:

```php
<?php

use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\NodeInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ProxyMiddleware
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        private ThrottlerInterface $throttler,
        private CollectionInterface $collection,
        private array $context = [],
    ) {
    }

    public function __invoke(callable $handler): \Closure
    {
        return function (RequestInterface $request, array $options) use ($handler): ResponseInterface {
            /** @var NodeInterface $node */
            $node = $this->throttler->pick($this->collection, $this->context);
            
            $options['proxy'] = $node->getName();

            return $handler($request, $options);
        };
    }
}
```

Create simple in-memory storage in Redis to keep load balancing counting between PHP calls for *round-robin based* strategies. Below is an example of how to implement the in-memory counter with the help of the `predis/predis` package:

```php
<?php

use Orangesoft\Throttler\Counter\CounterInterface;
use Predis\Client as RedisClient;

final class RedisCounter implements CounterInterface
{
    public function __construct(
        private RedisClient $redis,
    ) {
    }

    public function next(string $name = 'default'): int
    {
        if (!$this->redis->exists($name)) {
            $this->redis->set($name, -1);
        }

        return $this->redis->incr($name);
    }
}
```

Now it’s time to configure load balancer and connect proxy middleware to Guzzle:

```php
<?php

use Predis\Client as RedisClient;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

$redis = new RedisClient('tcp://127.0.0.1:6379');

$throttler = new WeightedRoundRobinThrottler(
    new RedisCounter($redis),
);

$collection = new InMemoryCollection([
    new Node('user:pass@192.168.0.1', 5),
    new Node('user:pass@192.168.0.2', 1),
    new Node('user:pass@192.168.0.3', 1),
    new Node('user:pass@192.168.0.4', 1),
]);

$stack = HandlerStack::create();
$stack->push(new ProxyMiddleware($throttler, $collection));
$guzzle = new GuzzleClient(['handler' => $stack]);
```

We can use Guzzle as always:

```php
while (true) {
    /** @var ResponseInterface $response */
    $response = $guzzle->get('https://httpbin.org/ip');

    // ...
}
```

The result of the proxy balancing will be as follows:

```text
+---------+-----------------------+
| request | proxy                 |
+---------+-----------------------+
|       1 | user:pass@192.168.0.1 |
|       2 | user:pass@192.168.0.1 |
|       3 | user:pass@192.168.0.1 |
|       4 | user:pass@192.168.0.1 |
|       5 | user:pass@192.168.0.1 |
|       6 | user:pass@192.168.0.2 |
|       7 | user:pass@192.168.0.3 |
|       8 | user:pass@192.168.0.4 |
|       n | etc.                  |
+---------+-----------------------+
```

Proxy balancing in Guzzle is one of the package's use cases. You can use it for distributing requests across different microservices to ensure even utilization and prevent bottlenecks, read-only database queries across multiple database servers to improve performance, API requests across multiple backend services to ensure high availability and fault tolerance, etc. 
