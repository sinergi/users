<?php

namespace Sinergi\Users\Session\Exception;

use Sinergi\Users\Session\SessionException;

class SessionExpiredException extends SessionException
{
    public function __construct()
    {
        parent::__construct('Session expired', 1101);
    }
}
