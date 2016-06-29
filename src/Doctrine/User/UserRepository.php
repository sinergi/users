<?php

namespace Sinergi\Users\Doctrine\User;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;

/**
 * @method UserEntity find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method UserEntity findOneBy(array $criteria, array $orderBy = null)
 * @method UserEntity findOneById(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByEmail(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByEmailConfirmationToken(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByPasswordResetToken(array $criteria, array $orderBy = null)
 */
class UserRepository extends EntityRepository
{
}
