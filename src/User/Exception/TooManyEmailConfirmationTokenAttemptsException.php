<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class TooManyEmailConfirmationTokenAttemptsException extends UserException
{
    public function __construct(
        string $message = 'Too many email confirmation token attempts',
        int $code = 1202,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
