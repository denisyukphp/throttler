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
+-------------------------------+------+-----+---------+-------+-------+--------+-------+
| benchmark                     | revs | its | mean    | min   | max   | sum    | diff  |
+-------------------------------+------+-----+---------+-------+-------+--------+-------+
| RandomBench                   | 1000 | 5   | 1.003μs | 0.963 | 1.034 | 5.014  | 1.00x |
| WeightedRandomBench           | 1000 | 5   | 2.524μs | 2.337 | 2.739 | 12.62  | 2.52x |
| FrequencyRandomBench          | 1000 | 5   | 1.778μs | 1.727 | 1.829 | 8.889  | 1.77x |
| RoundRobinBench               | 1000 | 5   | 1.028μs | 0.982 | 1.073 | 5.139  | 1.02x |
| WeightedRoundRobinBench       | 1000 | 5   | 2.609μs | 2.525 | 2.668 | 13.046 | 2.60x |
| SmoothWeightedRoundRobinBench | 1000 | 5   | 1.882μs | 1.803 | 1.984 | 9.408  | 1.88x |
+-------------------------------+------+-----+---------+-------+-------+--------+-------+
```

The report is based on measuring the speed. Check `diff` column to find out which strategy is the fastest. You can see that the fastest strategies are Random and RoundRobin.

## Documentation

- [Configuration](docs/index.md#configuration)
- [Available strategies](docs/index.md#available-strategies)
- [Sort nodes](docs/index.md#sort-nodes)
- [Keep counter](docs/index.md#keep-counter)
- [Serialize strategies](docs/index.md#serialize-strategies)
- [Supported tools](docs/index.md#supported-tools)

Read more about usage on [Orangesoft Tech](https://orangesoft.co/blog/how-to-make-proxy-balancing-in-guzzle).
