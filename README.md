# Throttler

[![Build Status](https://img.shields.io/github/actions/workflow/status/denisyukphp/throttler/ci.yml?branch=main&style=plastic)](https://github.com/denisyukphp/throttler/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/orangesoft/throttler?style=plastic)](https://packagist.org/packages/orangesoft/throttler)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/orangesoft/throttler?style=plastic&color=8892BF)](https://packagist.org/packages/orangesoft/throttler)
[![Total Downloads](https://img.shields.io/packagist/dt/orangesoft/throttler?style=plastic)](https://packagist.org/packages/orangesoft/throttler)
[![License](https://img.shields.io/packagist/l/orangesoft/throttler?style=plastic&color=428F7E)](https://packagist.org/packages/orangesoft/throttler)

Load balancer between nodes.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require orangesoft/throttler
```

This package requires PHP 8.1 or later.

## Quick usage

Configure `Orangesoft\Throttler\WeightedRoundRobinThrottler::class` as below and set weight for each node as the second argument in constructor if you are using weighted strategies:

```php
<?php

use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\WeightedRoundRobinThrottler;

$throttler = new WeightedRoundRobinThrottler(
    new InMemoryCounter(),
);

$collection = new InMemoryCollection([
    new Node('192.168.0.1', 5),
    new Node('192.168.0.2', 1),
    new Node('192.168.0.3', 1),
]);
```
Use `Orangesoft\Throttler\ThrottlerInterface::pick(Orangesoft\Throttler\Collection\CollectionInterface $collection, array $context = []): Orangesoft\Throttler\Collection\NodeInterface` method to pick node according to the chosen strategy:

```php
while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->pick($collection);

    // ...
}
```

As a result, the strategy will go through all the nodes and return the appropriate one like below:

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

The following load balancing strategies are available:

- [Orangesoft\Throttler\RandomThrottler](../src/RandomThrottler.php)
- [Orangesoft\Throttler\WeightedRandomThrottler](../src/WeightedRandomThrottler.php)
- [Orangesoft\Throttler\FrequencyRandomThrottler](../src/FrequencyRandomThrottler.php)
- [Orangesoft\Throttler\RoundRobinThrottler](../src/RoundRobinThrottler.php)
- [Orangesoft\Throttler\WeightedRoundRobinThrottler](../src/WeightedRoundRobinThrottler.php)
- [Orangesoft\Throttler\SmoothWeightedRoundRobinThrottler](../src/SmoothWeightedRoundRobinThrottler.php)

## Benchmarks

Run `composer phpbench` to check out benchmarks:

```text
+-------------------------------+------+-----+----------+----------+----------+---------+
| benchmark                     | revs | its | mean     | best     | worst    | stdev   |
+-------------------------------+------+-----+----------+----------+----------+---------+
| RandomBench                   | 1000 | 5   | 4.002μs  | 3.880μs  | 4.097μs  | 0.073μs |
| WeightedRandomBench           | 1000 | 5   | 11.660μs | 11.533μs | 11.797μs | 0.094μs |
| FrequencyRandomBench          | 1000 | 5   | 6.074μs  | 5.924μs  | 6.242μs  | 0.139μs |
| RoundRobinBench               | 1000 | 5   | 4.060μs  | 3.888μs  | 4.363μs  | 0.171μs |
| WeightedRoundRobinBench       | 1000 | 5   | 10.778μs | 10.655μs | 10.919μs | 0.115μs |
| SmoothWeightedRoundRobinBench | 1000 | 5   | 6.888μs  | 6.707μs  | 7.102μs  | 0.130μs |
+-------------------------------+------+-----+----------+----------+----------+---------+
```

The report is based on measuring the speed. Check `best` column to find out which strategy is the fastest.

## Documentation

- [How it works](docs/index.md##how-it-works)
- [Available strategies](docs/index.md##available-strategies)
- [Keep states](docs/index.md##keep-states)
    - [Counting](docs/index.md##counting)
    - [Serialization](docs/index.md##serialization)
- [Choice from multiple](docs/index.md##choice-from-multiple)
- [Balance cluster](docs/index.md##balance-cluster)
- [Production example](docs/index.md##production-example)

Read more about [Load Balancing](https://samwho.dev/load-balancing/).
