<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection\Exception;

class UnweightedCollectionException extends \LogicException
{
    public function __construct(string $message = 'All nodes in the collection must be weighted.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
