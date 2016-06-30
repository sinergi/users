<?php

namespace Sinergi\Users\Doctrine\User;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Sinergi\Users\User\UserRepositoryInterface;

/**
 * @method UserEntity find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method UserEntity findOneBy(array $criteria, array $orderBy = null)
 * @method UserEntity findOneById(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByEmail(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByEmailConfirmationToken(array $criteria, array $orderBy = null)
 * @method UserEntity findOneByPasswordResetToken(array $criteria, array $orderBy = null)
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /** @return UserEntity|null */
    public function findByEmail(string $email)
    {
        $result = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();

        return $result && $result[0] ? $result[0] : null;
    }
}
