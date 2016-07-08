<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class InvalidEmailConfirmationTokenException extends UserException
{
    public function __construct(
        string $message = 'Invalid email confirmation token',
        int $code = 1203,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
