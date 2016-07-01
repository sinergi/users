<?php

namespace Sinergi\Users\Authentication\Exception;

use Sinergi\Users\Authentication\AuthenticationException;

class AccountInvalidException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('Account invalid', 1003);
    }
}
