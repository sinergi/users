<?php

namespace Sinergi\Users\Session\Exception;

use Sinergi\Users\Session\SessionException;

class InvalidUserException extends SessionException
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Invalid user', 1200);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
