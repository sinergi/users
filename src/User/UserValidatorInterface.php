<?php

namespace Sinergi\Users\User;

use Interop\Container\ContainerInterface;

interface UserValidatorInterface
{
    public function __construct(ContainerInterface $container);
    public function __invoke(UserEntityInterface $user): array;
}
