<?php

namespace App\Exception;

class BookNotFoundException extends \Exception
{
    public function __construct(string $message = 'Books not found', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
