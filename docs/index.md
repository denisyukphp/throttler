# Documentation

- [How it works](#how-it-works)
- [Available strategies](#available-strategies)
- [Keep states](#keep-states)
  - [Counting](#counting)
  - [Serialization](#serialization)
- [Choice from multiple](#choice-from-multiple)
- [Balance cluster](#balance-cluster)
- [Production example](#production-example)

## How it works

[...]

## Available strategies

[...]

- [Orangesoft\Throttler\RandomThrottler](../src/RandomThrottler.php)
- [Orangesoft\Throttler\WeightedRandomThrottler](../src/WeightedRandomThrottler.php)
- [Orangesoft\Throttler\FrequencyRandomThrottler](../src/FrequencyRandomThrottler.php)
- [Orangesoft\Throttler\RoundRobinThrottler](../src/RoundRobinThrottler.php)
- [Orangesoft\Throttler\WeightedRoundRobinThrottler](../src/WeightedRoundRobinThrottler.php)
- [Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler](../src/SmoothWeightedRoundRobinThrottler.php)

## Keep states

[...]

```text
+--------------------------+---------------+
| Throttler                | Method        |
+--------------------------+---------------+
| Random                   | [x]           |
| WeightedRandom           | [x]           |
| FrequencyRandom          | [x]           |
| RoundRobin               | counting      |
| WeightedRoundRobin       | counting      |
| SmoothWeightedRoundRobin | serialization |
+--------------------------+---------------+
```

[...]

### Counting

[...]

```text
composer require predis/predis
```

[...]

```php
<?php

use Orangesoft\Throttler\Counter\CounterInterface;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;

final class RedisCounter implements CounterInterface
{
    public function __construct(
        private readonly Predis\Client $client,
    ) {
    }

    public function next(string $name = 'default', int $start = 0): int
    {
        if (!$this->client->exists($name)) {
            $this->client->set($name, $start - 1);
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
```

[...]

### Serialization

[...]



## Choice from multiple

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
    new ClusterSet(new RoundRobinThrottler(new InMemoryCounter()), ['cluster1']),
    new ClusterSet(new RandomThrottler(), ['cluster2', 'cluster3']),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1'),
    new Node('192.168.0.2'),
    new Node('192.168.0.3'),
]);

$cluster = new Cluster('cluster1', $collection);
```

[...]

```php
/** @var NodeInterface $node */
$node = $cluster->balance($pool);
```

[...]

## Production example

[...]

```text
composer require \
    && orangesoft/throttler \
    && guzzlehttp/guzzle \
    && psr/http-message
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
    * @param ThrottlerInterface $throttler
    * @param CollectionInterface $collection
    * @param array<string, mixed> $context
     */
    public function __construct(
        private readonly ThrottlerInterface $throttler,
        private readonly CollectionInterface $collection,
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
$throttler = new WeightedRoundRobinThrottler(
    new InMemoryCounter(),
);

$collection = new InMemoryCollection([
    new Node('user:pass@192.168.0.1', 5),
    new Node('user:pass@192.168.0.2', 1),
    new Node('user:pass@192.168.0.3', 1),
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
+-------------+
| 192.168.0.1 |
| 192.168.0.1 |
| 192.168.0.1 |
| 192.168.0.1 |
| 192.168.0.1 |
| 192.168.0.2 |
| 192.168.0.3 |
| etc.        |
+-------------+
```

[...]
