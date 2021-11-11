<?php

namespace Meow\Routing\Exceptions;

use Throwable;

class NotFoundRouteException extends \Exception
{
    public function __construct(string $message = "", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}