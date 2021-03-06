<?php

namespace Sinergi\Users\User;

interface UserRepositoryInterface
{
    /** @return UserEntityInterface */
    public function findByEmail(string $email);
    /** @return UserEntityInterface */
    public function findById(int $id);
    /** @return UserEntityInterface */
    public function findByResetPasswordToken(string $token);
    /** @return UserEntityInterface[] */
    public function findByGroupId(int $id);
    public function save(UserEntityInterface $user);
}
