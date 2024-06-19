# Documentation

- [Available throttlers](#available-throttlers)
  - [Random](#random)
  - [Weighted random](#weighted-random)
  - [Frequency random](#frequency-random)
  - [Round-robin](#round-robin)
  - [Weighted round-robin](#weighted-round-robin)
  - [Smooth weighted round-robin](#smooth-weighted-round-robin)
- [Keep states](#keep-states)
  - [Use counter](#use-counter)
  - [Use serialization](#use-serialization)
- [Custom counter](#custom-counter)
- [Custom strategy](#custom-strategy)
- [Multiple throttler](#multiple-throttler)
- [Balance cluster](#balance-cluster)
- [Guzzle middleware](#guzzle-middleware)

## Available throttlers

The following throttlers are available:

### Random

[...]

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

[...]

```text
+---------+-------------+--------+
| request | node        | chance |
+---------+-------------+--------+
|       1 | 192.168.0.1 |  25.0% |
|       2 | 192.168.0.2 |  25.0% |
|       3 | 192.168.0.3 |  25.0% |
|       3 | 192.168.0.4 |  25.0% |
|       n | etc.        |        |
+---------+-------------+--------+
```

[...]

### Weighted random

[...]

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

[...]

```text
+---------+-------------+--------+
| request | node        | chance |
+---------+-------------+--------+
|       1 | 192.168.0.1 |  62.5% |
|       2 | 192.168.0.2 |  12.5% |
|       3 | 192.168.0.3 |  12.5% |
|       3 | 192.168.0.4 |  12.5% |
|       n | etc.        |        |
+---------+-------------+--------+
```

[...]

### Frequency random

[...]

```php
<?php

use Orangesoft\Throttler\FrequencyRandomThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = new FrequencyRandomThrottler(
    frequency: 0.8,
    threshold: 0.2,
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

[...]

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

[...]

### Round-robin

[...]

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

[...]

```text
+---------+-------------+
| request | node        |
+---------+-------------+
|       1 | 192.168.0.1 |
|       2 | 192.168.0.2 |
|       3 | 192.168.0.3 |
|       3 | 192.168.0.4 |
|       n | etc.        |
+---------+-------------+
```

[...]

### Weighted round-robin

[...]

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

[...]

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

[...]

### Smooth weighted round-robin

[...]

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

[...]

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

[...]

## Keep states

[...]

```text
+-----------------------------+---------------+
| Strategy                    | Method        |
+-----------------------------+---------------+
| Random                      | [x]           |
| Weighted random             | [x]           |
| Frequency random            | [x]           |
| Round-robin                 | counter       |
| Weighted round-robin        | counter       |
| Smooth weighted round-robin | serialization |
+-----------------------------+---------------+
```

[...]

### Use counter

[...]

```php
<?php

use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\InMemoryCollection;

$throttler = new RoundRobinThrottler(
    new InMemoryCounter(),
)

$collection = new \Orangesoft\Throttler\Collection\InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

$counter = 0;

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection, [
        'counter' => 'other',
    ]);

    // ...
    
    $counter++;
}
```

[...]

```php
<?php

use Orangesoft\Throttler\RoundRobinThrottler;
use Orangesoft\Throttler\Counter\InMemoryCounter;

$counter = 100;

$throttler = new RoundRobinThrottler(
    new InMemoryCounter(
        start: $counter,
    ),
);
```

[...]

### Use serialization

[...]

```php
<?php

use Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\NodeInterface;

$throttler = SmoothWeightedRoundRobinThrottler();

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

[...]

```php
/** @var SmoothWeightedRoundRobinThrottler $throttler */
$throttler = unserialize($serialized);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

[...]

## Custom counter

[...]

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

[...]

## Custom strategy

[...]

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

[...]

## Multiple throttler

[...]

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

[...]

```php
/** @var NodeInterface $node */
$node = $throttler->pick($collection, [
    'throttler' => RoundRobinStrategy::class,
]);
```

[...]

## Balance cluster

[...]

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
    new ClusterSet(new RoundRobinThrottler(new InMemoryCounter()), ['a']),
    new ClusterSet(new RandomThrottler(), ['b', 'c']),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
    new Node('192.168.0.4'),
]);

$cluster = new Cluster('a', $collection);
```

[...]

```php
/** @var NodeInterface $node */
$node = $cluster->balance($pool);
```

[...]

## Guzzle middleware

[...]

```text
composer require \
    && orangesoft/throttler \
    && guzzlehttp/guzzle \
    && psr/http-message \
    && predis/predis
```

[...]

```php
<?php

use Orangesoft\Throttler\ThrottlerInterface;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;
use Orangesoft\Throttler\Collection\CollectionInterface;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
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

[...]

```php
<?php

use Orangesoft\Throttler\Counter\CounterInterface;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;

final class RedisCounter implements CounterInterface
{
    public function __construct(
        private Predis\Client $client,
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

[...]

```php
/** @var Predis\Client $client */
$client = new Client('tcp://127.0.0.1:6379');

$throttler = new WeightedRoundRobinThrottler(
    new RedisCounter($client),
);

$collection = new InMemoryCollection([
    new Node('user:pass@192.168.0.1', 5),
    new Node('user:pass@192.168.0.2', 1),
    new Node('user:pass@192.168.0.3', 1),
    new Node('user:pass@192.168.0.4', 1),
]);

$stack = HandlerStack::create();
$stack->push(new ProxyMiddleware($throttler, $collection));
$client = new Client(['handler' => $stack]);
```

[...]

```php
while (true) {
    /** @var ResponseInterface $response */
    $response = $client->get('https://httpbin.org/ip');

    // ...
}
```

[...]

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

[...]
