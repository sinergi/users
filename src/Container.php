<?php

namespace Sinergi\Users;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Sinergi\Users\Doctrine\User\UserEntity as DoctrineUserEntity;
use Sinergi\Users\Doctrine\User\UserRepository as DoctrineUserRepository;
use Sinergi\Users\Eloquent\User\UserEntity as EloquentUserEntity;
use Sinergi\Users\Eloquent\User\UserRepository as EloquentUserRepository;
use Sinergi\Users\Doctrine\Session\SessionEntity as DoctrineSessionEntity;
use Sinergi\Users\Doctrine\Session\SessionRepository as DoctrineSessionRepository;
use Sinergi\Users\Eloquent\Session\SessionEntity as EloquentSessionEntity;
use Sinergi\Users\Eloquent\Session\SessionRepository as EloquentSessionRepository;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class Container implements ContainerInterface
{
    private $container;
    private $items = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->init();
    }

    private function init()
    {
        $hasUserEntity = $this->container->has(UserEntityInterface::class);
        $hasUserRepository = $this->container->has(UserRepositoryInterface::class);
        $hasSessionEntity = $this->container->has(SessionEntityInterface::class);
        $hasSessionRepository = $this->container->has(SessionRepositoryInterface::class);

        if ($hasUserEntity && !$hasUserRepository) {
            $userEntity = $this->container->get(UserEntityInterface::class);
            if ($userEntity instanceof DoctrineUserEntity) {
                $em = $this->container->get(EntityManager::class);
                $this->items[UserRepositoryInterface::class] = function () use ($em, $userEntity) {
                    return new DoctrineUserRepository($em, $em->getClassMetadata(get_class($userEntity)));
                };
            } elseif ($userEntity instanceof EloquentUserEntity) {
                $this->items[UserRepositoryInterface::class] = function () {
                    return new EloquentUserRepository();
                };
            }
        }

        if ($hasSessionEntity && !$hasSessionRepository) {
            $sessionEntity = $this->container->get(SessionEntityInterface::class);
            if ($sessionEntity instanceof DoctrineSessionEntity) {
                $em = $this->container->get(EntityManager::class);
                $this->items[SessionRepositoryInterface::class] = function () use ($em, $sessionEntity) {
                    return new DoctrineSessionRepository($em, $em->getClassMetadata(get_class($sessionEntity)));
                };
            } elseif ($sessionEntity instanceof EloquentSessionEntity) {
                $this->items[SessionRepositoryInterface::class] = function () {
                    return new EloquentSessionRepository();
                };
            }
        }
    }

    public function get($id)
    {
        if (isset($this->items[$id])) {
            $item = $this->items[$id];
            if (is_callable($item)) {
                return call_user_func($item);
            } else {
                return $item;
            }
        }
        return $this->container->get($id);
    }

    public function has($id): bool
    {
        if (isset($this->items[$id])) {
            return true;
        }
        return $this->container->has($id);
    }
}
