<?php

namespace Sinergi\Users\Authentication\Exception;

use Sinergi\Users\Authentication\AuthenticationException;

class AccountDeletedException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('Account deleted', 1002);
    }
}
