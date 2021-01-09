# Throttler

[![Build Status](https://travis-ci.com/Orangesoft-Development/throttler.svg?branch=main)](https://travis-ci.com/Orangesoft-Development/throttler)
[![Latest Stable Version](https://poser.pugx.org/orangesoft/throttler/v)](//packagist.org/packages/orangesoft/throttler) 
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/orangesoft/throttler)](https://packagist.org/packages/orangesoft/throttler)
[![Total Downloads](https://poser.pugx.org/orangesoft/throttler/downloads)](https://packagist.org/packages/orangesoft/throttler)
[![License](https://poser.pugx.org/orangesoft/throttler/license)](https://packagist.org/packages/orangesoft/throttler)

```text
composer require orangesoft/throttler
```

This package requires PHP 7.2 or later.

## Quick usage

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

$throttler = new Throttler(
    new Collection($nodes),
    new WeightedRoundRobinStrategy(
        new InMemoryCounter()
    )
);

while (true) {
    /** @var Node $node */
    $node = $throttler->next();
    
    // ...
}
```

Set weight for Node as the second argument in constructor if you are using weighted-strategies.

## Benchmarks

Run `composer bench` to check out strategies benchmarks from the package:

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

## Strategies

- [Random](src/Strategy/RandomStrategy.php)
- [WeightedRandom](src/Strategy/WeightedRandomStrategy.php)
- [FrequencyRandom](src/Strategy/FrequencyRandomStrategy.php)
- [RoundRobin](src/Strategy/RoundRobinStrategy.php)
- [WeightedRoundRobin](src/Strategy/WeightedRoundRobinStrategy.php)
- [SmoothWeightedRoundRobin](src/Strategy/SmoothWeightedRoundRobinStrategy.php)

Read more about load balancing and algorithms on [Wikipedia](https://en.wikipedia.org/wiki/Load_balancing_(computing)).
