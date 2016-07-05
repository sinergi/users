<?php

namespace Sinergi\Users\Group;

interface GroupRepositoryInterface
{
    public function save(GroupEntityInterface $group);
    /** @return GroupEntityInterface|null */
    public function findById(int $id);
}
