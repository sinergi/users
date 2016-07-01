<?php

namespace Sinergi\Users\Session;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Session\Exception\InvalidSessionException;
use Sinergi\Users\Session\Exception\SessionExpiredException;
use Sinergi\Users\Session\Exception\SessionNotFoundException;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class SessionController
{
    private $container;
    private $session;

    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    public function getSession(string $id): SessionEntityInterface
    {
        /** @var SessionRepositoryInterface $sessionRepository */
        $sessionRepository = $this->container->get(SessionRepositoryInterface::class);

        /** @var UserRepositoryInterface $sessionRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);

        $session = $sessionRepository->findById($id);
        if ($session instanceof SessionEntityInterface) {
            $session->setUserRepository($userRepository);
            if ($session->isExpired()) {
                throw new SessionExpiredException;
            } elseif (!$session->isValid()) {
                throw new InvalidSessionException;
            }
            return $this->session = $session;
        }

        throw new SessionNotFoundException;
    }

    public function createSession(UserEntityInterface $user, $isLongSession = false): SessionEntityInterface
    {
        $session = $this->container->get(SessionEntityInterface::class);
        $session->setUser($user);
        $session->setIsLongSession($isLongSession);

        /** @var SessionRepositoryInterface $sessionRepository */
        $sessionRepository = $this->container->get(SessionRepositoryInterface::class);
        $sessionRepository->save($session);

        return $this->session = $session;
    }

    public function deleteSession(SessionEntityInterface $session)
    {
        /** @var SessionRepositoryInterface $sessionRepository */
        $sessionRepository = $this->container->get(SessionRepositoryInterface::class);
        $sessionRepository->delete($session);
    }
}
