<?php

namespace Sinergi\Users\Group\Exception;

use Sinergi\Users\Group\GroupException;

class InvalidGroupException extends GroupException
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Invalid group', 1400);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
