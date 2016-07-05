<?php

namespace Sinergi\Users\Group;

use Interop\Container\ContainerInterface;

interface GroupValidatorInterface
{
    public function __construct(ContainerInterface $container);
    public function __invoke(GroupEntityInterface $group): array;
}
