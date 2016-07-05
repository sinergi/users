<?php

namespace Sinergi\Users\Doctrine\Group;

use Doctrine\ORM\EntityRepository;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupRepositoryInterface;

class GroupRepository extends EntityRepository implements GroupRepositoryInterface
{
    public function save(GroupEntityInterface $group)
    {
        $this->getEntityManager()->persist($group);
        $this->getEntityManager()->flush($group);
    }
}
