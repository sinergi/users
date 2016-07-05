<?php

namespace Sinergi\Users\Doctrine\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Group\GroupRepositoryInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    private $container;

    public function __construct(EntityManager $em, ClassMetadata $class, ContainerInterface $container)
    {
        parent::__construct($em, $class);
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    /** @return UserEntity */
    public function findByEmail(string $email)
    {
        $result = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();


        if ($result && $result[0]) {
            /** @var UserEntityInterface $user */
            $user = $result[0];
            $user->setGroupRepository($this->container->get(GroupRepositoryInterface::class));
            $user->setSessionRepository($this->container->get(SessionRepositoryInterface::class));
            return $user;
        }

        return null;
    }

    /** @return UserEntity */
    public function findById(int $id)
    {
        $result = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if ($result && $result[0]) {
            /** @var UserEntityInterface $user */
            $user = $result[0];
            $user->setGroupRepository($this->container->get(GroupRepositoryInterface::class));
            $user->setSessionRepository($this->container->get(SessionRepositoryInterface::class));
            return $user;
        }

        return null;
    }

    /** @return UserEntityInterface[] */
    public function findByGroupId(int $id)
    {
        $result = $this->createQueryBuilder('u')
            ->where('u.groupId = :groupId')
            ->setParameter('groupId', $id)
            ->getQuery()
            ->getResult();

        if ($result && $result[0]) {
            /** @var UserEntityInterface $user */
            foreach ($result as $user) {
                $user->setGroupRepository($this->container->get(GroupRepositoryInterface::class));
                $user->setSessionRepository($this->container->get(SessionRepositoryInterface::class));
            }
            return $result;
        }

        return [];
    }

    public function save(UserEntityInterface $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }
}
