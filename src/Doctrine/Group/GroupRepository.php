<?php

namespace Sinergi\Users\Doctrine\Group;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupRepositoryInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class GroupRepository extends EntityRepository implements GroupRepositoryInterface
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

    public function save(GroupEntityInterface $group)
    {
        $this->getEntityManager()->persist($group);
        $this->getEntityManager()->flush($group);
    }

    /** @return GroupEntityInterface|null */
    public function findById(int $id)
    {
        $result = $this->createQueryBuilder('g')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if ($result && $result[0]) {
            /** @var GroupEntityInterface $group */
            $group = $result[0];
            $group->setUserRepository(UserRepositoryInterface::class);
            return $group;
        }

        return null;
    }
}
