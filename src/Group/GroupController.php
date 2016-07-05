<?php

namespace Sinergi\Users\Group;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;

class GroupController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }
}
