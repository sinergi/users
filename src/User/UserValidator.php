<?php

namespace Sinergi\Users\User;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;

class UserValidator implements UserValidatorInterface
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

    /**
     * @param UserEntityInterface $user
     * @return array
     */
    public function __invoke(UserEntityInterface $user): array
    {
        $errors = [];

        if (empty($user->getEmail())) {
            $errors[1302] = 'Email is empty';
        } elseif (strlen($user->getEmail()) > 255) {
            $errors[1303] = 'Email is too long';
        } else {
            /** @var UserRepositoryInterface $userRepository */
            $userRepository = $this->container->get(UserRepositoryInterface::class);
            $userExists = $userRepository->findByEmail($user->getEmail());
            if ($userExists && (!$user->getId() || ($user->getId() !== $userExists->getId()))) {
                $errors[1300] = 'Email is already in use';
            }
        }

        if (empty($user->getPassword())) {
            $errors[1301] = 'Password is empty';
        }

        return $errors;
    }
}
