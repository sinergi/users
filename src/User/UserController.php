<?php

namespace Sinergi\Users\User;

use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Throttle\Throttle;
use Sinergi\Users\User\Exception\EmailAlreadyConfirmedException;
use Sinergi\Users\User\Exception\EmailConfirmationTokenBlockedException;
use Sinergi\Users\User\Exception\EmailConfirmationTokenExpiredException;
use Sinergi\Users\User\Exception\InvalidEmailConfirmationTokenException;
use Sinergi\Users\User\Exception\InvalidPasswordResetTokenException;
use Sinergi\Users\User\Exception\InvalidUserException;
use Sinergi\Users\User\Exception\PasswordResetTokenBlockedException;
use Sinergi\Users\User\Exception\PasswordResetTokenExpiredException;
use Sinergi\Users\User\Exception\TooManyEmailConfirmationTokenAttemptsException;
use DateInterval;
use Sinergi\Users\User\Exception\UserNotFoundException;

class UserController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    public function generateEmailConfirmationToken(
        UserEntityInterface $user,
        string $token = null,
        DateInterval $expiration = null,
        bool $save = true
    ): UserEntityInterface {
        if ($user->isEmailConfirmed()) {
            throw new EmailAlreadyConfirmedException;
        } elseif ($user->getEmailConfirmationToken() && !$user->hasEmailConfirmationTokenCooldownExpired()) {
            throw new EmailConfirmationTokenBlockedException;
        }

        $user->generateEmailConfirmationToken($token, $expiration);
        if ($save) {
            /** @var UserRepositoryInterface $userRepository */
            $userRepository = $this->container->get(UserRepositoryInterface::class);
            $userRepository->save($user);
        }
        return $user;
    }

    public function generatePassswordResetToken(
        string $email,
        string $token = null,
        DateInterval $expiration = null,
        string $ip = null,
        bool $save = true
    ): UserEntityInterface {
        if ($ip) {
            Throttle::throttle($ip);
        }
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $user = $userRepository->findByEmail($email);
        if (!$user) {
            throw new UserNotFoundException;
        } elseif ($user->getPasswordResetToken() && !$user->hasPasswordResetTokenCooldownExpired()) {
            throw new PasswordResetTokenBlockedException;
        }

        $user->generatePasswordResetToken($token, $expiration);
        if ($save) {
            /** @var UserRepositoryInterface $userRepository */
            $userRepository = $this->container->get(UserRepositoryInterface::class);
            $userRepository->save($user);
        }
        return $user;
    }

    public function resetPassword(
        string $token,
        string $password,
        string $ip = null,
        bool $save = true
    ): UserEntityInterface {
        if ($ip) {
            Throttle::throttle($ip);
        }

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $user = $userRepository->findByResetPasswordToken($token);

        if (!$user) {
            throw new InvalidPasswordResetTokenException;
        } elseif ($user->hasPasswordResetTokenExpired()) {
            throw new PasswordResetTokenExpiredException;
        }

        $user->setPassword($password);

        /** @var UserValidatorInterface $userValidator */
        $userValidator = $this->container->get(UserValidatorInterface::class);
        $errors = $userValidator($user);

        if (count($errors)) {
            throw new InvalidUserException($errors);
        }

        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenExpirationDatetime(null);

        if ($save) {
            $userRepository->save($user);
        }

        return $user;
    }

    public function confirmEmail(UserEntityInterface $user, string $emailConfirmationToken): UserEntityInterface
    {
        if ($user->isEmailConfirmed()) {
            throw new EmailAlreadyConfirmedException;
        } elseif ($user->hasEmailConfirmationTokenExpired()) {
            throw new EmailConfirmationTokenExpiredException;
        } elseif ($user->hasTooManyEmailConfirmationTokenAttempts()) {
            throw new TooManyEmailConfirmationTokenAttemptsException;
        }

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);

        if ($user->getEmailConfirmationToken() !== $emailConfirmationToken) {
            $user->setEmailConfirmationTokenAttempts($user->getEmailConfirmationTokenAttempts() + 1);
            $userRepository->save($user);
            throw new InvalidEmailConfirmationTokenException;
        }

        $user->setIsEmailConfirmed(true);
        $user->setEmailConfirmationToken(null);
        $user->setEmailConfirmationTokenAttempts(0);
        $user->setEmailConfirmationTokenExpirationDatetime(null);
        $userRepository->save($user);
        return $user;
    }

    public function createUser(UserEntityInterface $user): UserEntityInterface
    {
        /** @var UserValidatorInterface $userValidator */
        $userValidator = $this->container->get(UserValidatorInterface::class);
        $errors = $userValidator($user);

        if (!empty($errors)) {
            throw new InvalidUserException($errors);
        }

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $userRepository->save($user);

        $this->getEmailConfirmationController()
            ->sendConfirmationEmail($user);

        return $user;
    }

    /**
     * @param UserEntity $userEntity
     * @param array      $parameters
     */
    public function updateUser(UserEntity $userEntity, array $parameters)
    {
        $options = [UserValidator::OPTION_SKIP_PASSWORD_VALIDATION];

        //todo : refactored this :

        $parameters = array_merge($userEntity->toArray(), $parameters);

        if ($userEntity->getEmail() === $parameters['email']) {
            $options[] = UserValidator::OPTION_SKIP_EMAIL_UNIQUENESS;
        }

        $this->getUserValidator()->validateParameters($parameters, $options);

        if ($userEntity->getEmail() !== $parameters['email']) {
            $userEntity->setPendingEmail($parameters['email']);

            (new EmailConfirmationController($this->getContainer()))->emailUpdated($userEntity);
        }

        $userEntity
            ->setName($parameters['name'])
            ->setPhone($parameters['phone']);

        $this->getEntityManager()->persist($userEntity);
        $this->getEntityManager()->flush($userEntity);
    }

    /**
     * @param userEntity $userEntity
     * @param array      $parameters
     * @throws AuthenticationException
     */
    public function updatePassword(userEntity $userEntity, array $parameters)
    {
        if (!$userEntity->testPassword($parameters['current-password'])) {
            throw new AuthenticationException($this->getDictionary()->get('user.password.error.wrong_password'));
        }

        $this->getUserValidator()->validateParameters($parameters,
            [UserValidator::OPTION_JUST_PASSWORD]);

        $userEntity->setPassword($parameters['password']);
        $this->getEntityManager()->persist($userEntity);
        $this->getEntityManager()->flush($userEntity);
    }

    /**
     * @param userEntity $userEntity
     */
    public function deleteUser(userEntity $userEntity)
    {
        $userEntity->setStatus(UserEntity::STATUS_DELETED);
        $userEntity->setDeletedEmail($userEntity->getEmail());
        $userEntity->setEmail(null);
        $this->getContainer()->getEntityManager()->persist($userEntity);
        $this->getContainer()->getEntityManager()->flush($userEntity);
    }

    /**
     * @param userEntity $userEntity
     */
    public function banUser(userEntity $userEntity)
    {
        $userEntity->setStatus(UserEntity::STATUS_BANNED);
        $userEntity->setDeletedEmail($userEntity->getEmail());
        $userEntity->setEmail(null);
        $this->getContainer()->getEntityManager()->persist($userEntity);
        $this->getContainer()->getEntityManager()->flush($userEntity);
    }

    /**
     * @param $status
     *
     * @return mixed
     */
    public function getUserStatusLabel($status)
    {
        switch ($status) {
            case UserEntity::STATUS_ACTIVE:
                return $this->getDictionary()->get('user.status.active');
            case UserEntity::STATUS_DELETED:
                return $this->getDictionary()->get('user.status.deleted');
                break;
            case UserEntity::STATUS_BANNED:
                return $this->getDictionary()->get('user.status.banned');
                break;
            default:
                return $this->getDictionary()->get('user.status.inactive');
        }
    }
}
