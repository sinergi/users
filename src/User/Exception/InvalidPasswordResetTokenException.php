<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class InvalidPasswordResetTokenException extends UserException
{
    public function __construct(
        string $message = 'Invalid password reset token',
        int $code = 1209,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
