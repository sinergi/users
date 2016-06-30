<?php

namespace Sinergi\Users\Authentication;

use Exception;
use Interop\Container\ContainerInterface;

class AuthenticationController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        
        if ($parameters instanceof UserEntity) {
            $user = $parameters;
        } else {
            $user = $this->getUserRepository()
                ->findOneByEmail($parameters['email']);
        }

        if (!($user instanceof UserEntity)
            || (is_array($parameters)
                && !$user->testPassword($parameters['password']))
        ) {
            throw new AuthenticationException(
                $this->getDictionary()
                    ->get('user.authentication.error.invalid_credentials')
            );
        }

        if (!$user->isActive()) {
            $statusLabel = (new UserController($this->getContainer()))
                ->getUserStatusLabel($user->getStatus());

            $accountDisabledError = $this->getDictionary()
                ->get('user.authentication.error.account_disabled');

            throw new AuthenticationException(
                sprintf($accountDisabledError, $statusLabel)
            );
        }

        try {
            $longSession = is_array($parameters) ?
                (bool)$parameters['is_long_session'] : false;

            $this->setSession(

                $this->getContainer()->getSessionController()->createSession(
                    $user,
                    $longSession
                )
            );

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
