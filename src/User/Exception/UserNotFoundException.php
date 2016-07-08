<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class UserNotFoundException extends UserException
{
    public function __construct(
        string $message = 'User not found',
        int $code = 1206,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
