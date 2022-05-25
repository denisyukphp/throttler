<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Collection\Exception;

class EmptyCollectionException extends \LogicException
{
    public function __construct(string $message = 'Collection of nodes must not be empty.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
