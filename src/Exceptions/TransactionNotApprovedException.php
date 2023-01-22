<?php

namespace Bolivir\Multipay\Exceptions;

use Exception;
use Throwable;

class TransactionNotApprovedException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('The given transaction ID is not yet approved', $code, $previous);
    }
}
