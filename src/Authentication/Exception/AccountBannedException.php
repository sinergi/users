<?php

namespace Sinergi\Users\Authentication\Exception;

use Sinergi\Users\Authentication\AuthenticationException;

class AccountBannedException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('Account banned', 1001);
    }
}
