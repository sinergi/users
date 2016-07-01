<?php

namespace Sinergi\Users\User;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;

class UserValidator
{
    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    public function __invoke(UserEntityInterface $user): array
    {
        $errors = [];

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $userExists = $userRepository->findByEmail($user->getEmail());
        if ($userExists && (!$user->getId() || ($user->getId() !== $userExists->getId()))) {
            $errors[1300] = 'Email is already in user';
        }

        if (empty($user->getPassword())) {
            $errors[1301] = 'Password is empty';
        }

        return $errors;
    }
}
