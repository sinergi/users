<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class PasswordResetTokenBlockedException extends UserException
{
    public function __construct(
        string $message = 'Password reset token blocked',
        int $code = 1207,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
