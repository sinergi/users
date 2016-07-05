<?php

namespace Sinergi\Users\Doctrine\Session;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class SessionRepository extends EntityRepository implements SessionRepositoryInterface
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

    public function save(SessionEntityInterface $session)
    {
        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush($session);
    }

    /** @return SessionEntityInterface */
    public function findById(string $id)
    {
        $result = $this->createQueryBuilder('s')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if ($result && $result[0]) {
            /** @var SessionEntityInterface $session */
            $session = $result[0];
            $session->setUserRepository($this->container->get(UserRepositoryInterface::class));
            return $session;
        }

        return null;
    }

    /** @return SessionEntityInterface[] */
    public function findByUserId(int $id)
    {
        $result = $this->createQueryBuilder('s')
            ->where('s.userId = :userId')
            ->setParameter('userId', $id)
            ->getQuery()
            ->getResult();

        if ($result && $result[0]) {
            /** @var SessionEntityInterface $session */
            foreach ($result as $session) {
                $session->setUserRepository($this->container->get(UserRepositoryInterface::class));
            }
            return $result;
        }

        return [];
    }

    public function delete(SessionEntityInterface $session)
    {
        $this->getEntityManager()->remove($session);
        $this->getEntityManager()->flush($session);
    }
}
