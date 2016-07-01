<?php

namespace Sinergi\Users\Authentication\Exception;

use Sinergi\Users\Authentication\AuthenticationException;

class InvalidCredentialsException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials', 1000);
    }
}
