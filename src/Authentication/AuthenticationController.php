<?php

namespace Sinergi\Users\Authentication;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Authentication\Exception\AccountBannedException;
use Sinergi\Users\Authentication\Exception\AccountDeletedException;
use Sinergi\Users\Authentication\Exception\AccountInvalidException;
use Sinergi\Users\Authentication\Exception\InvalidCredentialsException;
use Sinergi\Users\Container;
use Sinergi\Users\Session\SessionController;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class AuthenticationController
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

    public function login(string $email, string $password, bool $isLongSession = false)
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $user = $userRepository->findByEmail($email);

        if (!($user instanceof UserEntityInterface) || !$user->testPassword($password)) {
            throw new InvalidCredentialsException;
        }

        if (!$user->isActive()) {
            switch ($user->getStatus()) {
                case UserEntityInterface::STATUS_BANNED:
                    throw new AccountBannedException;
                case UserEntityInterface::STATUS_DELETED:
                    throw new AccountDeletedException;
                default:
                    throw new AccountInvalidException;
            }
        }

        $sessionController = new SessionController($this->container);
        return $sessionController->createSession($user, $isLongSession);
    }
}
