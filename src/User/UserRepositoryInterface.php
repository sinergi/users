<?php

namespace Sinergi\Users\User;

interface UserRepositoryInterface
{
    /** @return UserEntityInterface */
    public function findByEmail(string $email);
    /** @return UserEntityInterface */
    public function findById(int $id);
}
