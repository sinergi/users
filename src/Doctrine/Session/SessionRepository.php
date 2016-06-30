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
}
