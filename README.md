# Throttler

[![Build Status](https://img.shields.io/travis/com/Orangesoft-Development/throttler/main?style=plastic)](https://travis-ci.com/Orangesoft-Development/throttler)
[![Latest Stable Version](https://img.shields.io/packagist/v/orangesoft/throttler?style=plastic)](https://packagist.org/packages/orangesoft/throttler)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/orangesoft/throttler?style=plastic&color=8892BF)](https://packagist.org/packages/orangesoft/throttler)
[![Total Downloads](https://img.shields.io/packagist/dt/orangesoft/throttler?style=plastic)](https://packagist.org/packages/orangesoft/throttler)
[![License](https://img.shields.io/packagist/l/orangesoft/throttler?style=plastic&color=428F7E)](https://packagist.org/packages/orangesoft/throttler)

Throttler is the load balancer between nodes.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require orangesoft/throttler
```

This package requires PHP 7.2 or later.

## Quick usage

Configure Throttler as below:

```php
<?php

use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Throttler;

$nodes = [
    new Node('node1', 5),
    new Node('node2', 1),
    new Node('node3', 1),
];

$throttler = new Throttler(
    new Collection($nodes),
    new WeightedRoundRobinStrategy(
        new InMemoryCounter()
    )
);

while (true) {
    /** @var NodeInterface $node */
    $node = $throttler->next();
    
    $name = $node->getName();
    
    // ...
}
```

Set weight for Node as the second argument in constructor if you are using weighted-strategies.

## Benchmarks

Run `composer bench` to check out strategies benchmarks:

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

The report is based on measuring the speed. Check `best` column to find out which strategy is the fastest. You can see that the fastest strategies are Random and RoundRobin.

## Documentation

- [Configuration](docs/index.md#configuration)
- [Available strategies](docs/index.md#available-strategies)
- [Sort nodes](docs/index.md#sort-nodes)
- [Keep counter](docs/index.md#keep-counter)
- [Serialize strategies](docs/index.md#serialize-strategies)
- [Supported tools](docs/index.md#supported-tools)

Read more about usage on [Orangesoft Tech](https://orangesoft.co/blog/how-to-make-proxy-balancing-in-guzzle).
