<?php

namespace Sinergi\Users\Authentication;

use Exception;
use Interop\Container\ContainerInterface;
use Sinergi\Users\Container;
use Sinergi\Users\Session\SessionController;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

class AuthenticationController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = new Container($container);
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        try {
            $this->getAuthenticatedUser();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param array $parameters
     * @return UserEntity
     * @throws AuthenticationException
     */
    public function getUserByEmailAndPassword(array $parameters)
    {
        $user = $this->getUserRepository()
            ->findOneByEmail($parameters['email']);

        if ($user instanceof UserEntity && $user->testPassword($parameters['password'])) {
            return $user;
        }

        throw new AuthenticationException();
    }

    public function login(string $email, string $password, bool $isLongSession = false)
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->container->get(UserRepositoryInterface::class);
        $user = $userRepository->findByEmail($email);

        if (!($user instanceof UserEntityInterface) || !$user->testPassword($password)) {
            throw new AuthenticationException('Invalid credentials', 1000);
        }

        if (!$user->isActive()) {
            switch ($user->getStatus()) {
                case UserEntityInterface::STATUS_BANNED:
                    throw new AuthenticationException('Account banned', 1001);
                case UserEntityInterface::STATUS_DELETED:
                    throw new AuthenticationException('Account deleted', 1002);
                default:
                    throw new AuthenticationException('Account invalid', 1003);
            }
        }

        try {
            $sessionController = new SessionController($this->container);
            $sessionController->createSession($user, $isLongSession);

            $this->triggerEvent('user.login');

            if (!$user->isEmailConfirmed()) {
                throw new AuthenticationException(
                    $this->getDictionary()
                        ->get('user.authentication.error.email_not_confirmed')
                    . '<br><a href="#" data-action="resend-confirmation-email">' . $this->getDictionary()
                        ->get('user.authentication.error.resend_confirmation_email')
                    . '</a>'
                );
            }
            return $user;
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (SessionCreationException $e) {
            throw new AuthenticationException(
                $this->getDictionary()
                    ->get('user.authentication.error.internal_error')
            );
        }
    }

    public function disconnectUser()
    {
        $this->getContainer()->getSessionController()->deleteSession();
        $this->triggerEvent('user.logout');
    }

    /**
     * @return UserEntity
     * @throws Exception
     */
    public function getAuthenticatedUser()
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $session = $this->getSession();
        if ($session instanceof SessionEntity) {

            if (!$session->getUser()->isEmailConfirmed()) {
                throw new AuthenticationException(
                    $this->getDictionary()
                        ->get('user.authentication.error.email_not_confirmed')
                    . '<br><a href="#" data-action="resend-confirmation-email">' . $this->getDictionary()
                        ->get('user.authentication.error.resend_confirmation_email')
                    . '</a>'
                );
            }

            return $this->user = $session->getUser();
        }

        throw new Exception(
            $this->getDictionary()
                ->get('user.authentication.error.not_authenticated')
        );
    }

    /**
     * @return bool|UserEntity
     * @deprecated
     */
    public function getPendingUser()
    {

        $session = $this->getSession();
        if ($session instanceof SessionEntity) {

            if ($session->getUser()->isEmailConfirmed()) {
                return false;
            }

            return $session->getUser();
        }

        return false;
    }

    /**
     * @return UserEntity|null
     */
    public function getUser()
    {
        try {
            return $this->getAuthenticatedUser();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    private function triggerEvent($event)
    {
        $this->getContainer()->getEvenementEmitter()->emit($event);

        return $this;
    }
}
