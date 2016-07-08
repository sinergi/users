<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class PasswordResetTokenExpiredException extends UserException
{
    public function __construct(
        string $message = 'Password reset token expired',
        int $code = 1208,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
