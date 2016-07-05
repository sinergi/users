<?php

namespace Sinergi\Users\Group;

interface GroupRepositoryInterface
{
    public function save(GroupEntityInterface $group);
}
