<?php

namespace Sinergi\Users\Doctrine\Session;

use Doctrine\ORM\EntityRepository;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;

class SessionRepository extends EntityRepository implements SessionRepositoryInterface
{
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

        return $result && $result[0] ? $result[0] : null;
    }

    public function delete(SessionEntityInterface $session)
    {
        $this->getEntityManager()->remove($session);
        $this->getEntityManager()->flush($session);
    }
}
