<?php

namespace Sinergi\Users\Session\Exception;

use Sinergi\Users\Session\SessionException;

class InvalidSessionException extends SessionException
{
    public function __construct()
    {
        parent::__construct('Invalid session', 1100);
    }
}
