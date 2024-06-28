<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__.'/src/',
        __DIR__.'/tests/',
        __DIR__.'/benchmarks/',
    ])
;

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setCacheFile(__DIR__.'/build/cache/php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
