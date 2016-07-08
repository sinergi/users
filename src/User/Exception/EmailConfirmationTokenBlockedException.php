<?php

namespace Sinergi\Users\User\Exception;

use Exception;
use Sinergi\Users\User\UserException;

class EmailConfirmationTokenBlockedException extends UserException
{
    public function __construct(
        string $message = 'Email confirmation token blocked',
        int $code = 1201,
        Exception $exception = null
    ) {
        parent::__construct($message, $code, $exception);
    }
}
