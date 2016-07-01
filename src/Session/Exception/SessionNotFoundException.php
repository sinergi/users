<?php

namespace Sinergi\Users\Session\Exception;

use Sinergi\Users\Session\SessionException;

class SessionNotFoundException extends SessionException
{
    public function __construct()
    {
        parent::__construct('Session not found', 1102);
    }
}
