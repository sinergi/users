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
use Sinergi\Users\Doctrine\Group\GroupEntity as DoctrineGroupEntity;
use Sinergi\Users\Doctrine\Group\GroupRepository as DoctrineGroupRepository;
use Sinergi\Users\Eloquent\Group\GroupEntity as EloquentGroupEntity;
use Sinergi\Users\Eloquent\Group\GroupRepository as EloquentGroupRepository;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupRepositoryInterface;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;
use Sinergi\Users\User\UserValidator;
use Sinergi\Users\User\UserValidatorInterface;

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
        $self = $this;
        $hasUserEntity = $this->container->has(UserEntityInterface::class);
        $hasUserRepository = $this->container->has(UserRepositoryInterface::class);
        $hasSessionEntity = $this->container->has(SessionEntityInterface::class);
        $hasSessionRepository = $this->container->has(SessionRepositoryInterface::class);
        $hasGroupEntity = $this->container->has(GroupEntityInterface::class);
        $hasGroupRepository = $this->container->has(GroupRepositoryInterface::class);
        $hasUserValidator = $this->container->has(UserValidatorInterface::class);

        if ($hasUserEntity && !$hasUserRepository) {
            $userEntity = $this->container->get(UserEntityInterface::class);
            if ($userEntity instanceof DoctrineUserEntity) {
                $em = $this->container->get(EntityManager::class);
                $this->items[UserRepositoryInterface::class] = function () use ($em, $userEntity, $self) {
                    return new DoctrineUserRepository($em, $em->getClassMetadata(get_class($userEntity)), $self);
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
                $this->items[SessionRepositoryInterface::class] = function () use ($em, $sessionEntity, $self) {
                    return new DoctrineSessionRepository($em, $em->getClassMetadata(get_class($sessionEntity)), $self);
                };
            } elseif ($sessionEntity instanceof EloquentSessionEntity) {
                $this->items[SessionRepositoryInterface::class] = function () {
                    return new EloquentSessionRepository();
                };
            }
        }

        if ($hasGroupEntity && !$hasGroupRepository) {
            $groupEntity = $this->container->get(GroupEntityInterface::class);
            if ($groupEntity instanceof DoctrineGroupEntity) {
                $em = $this->container->get(EntityManager::class);
                $this->items[GroupRepositoryInterface::class] = function () use ($em, $groupEntity, $self) {
                    return new DoctrineGroupRepository($em, $em->getClassMetadata(get_class($groupEntity)), $self);
                };
            } elseif ($groupEntity instanceof EloquentGroupEntity) {
                $this->items[GroupRepositoryInterface::class] = function () {
                    return new EloquentGroupRepository();
                };
            }
        }

        if (!$hasUserValidator) {
            $self = $this;
            $this->items[UserValidatorInterface::class] = function () use ($self) {
                return new UserValidator($self);
            };
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
