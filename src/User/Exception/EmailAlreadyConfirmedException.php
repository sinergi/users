<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class EmailAlreadyConfirmedException extends UserException
{
    public function __construct(
        string $message = 'Email already confirmed',
        int $code = 1205,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
