<?php

namespace Sinergi\Users\Session;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sinergi\Users\Container;
use Sinergi\Users\User\UserEntityInterface;

class SessionController
{
    private $container;
    private $session;

    public function __construct(ContainerInterface $container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        } else {
            $this->container = new Container($container);
        }
    }

    /**
     * @return SessionEntity|null
     */
    public function getSession()
    {
        if (null !== $this->session) {
            return $this->session;
        }

        $headers = $this->getRequest()->headers();

        if (isset($headers['x-session']) && $sessionId = $headers['x-session']) {
            $session = $this->getSessionRepository()->find($sessionId);
            if ($session instanceof SessionEntity && $session->isValid()) {
                return $this->session = $session;
            }
        } else if ($sessionId = $this->getRequest()->cookies()
            ->get(SessionEntity::COOKIE_NAME)
        ) {
            $session = $this->getSessionRepository()->find($sessionId);
            if ($session instanceof SessionEntity && $session->isValid()) {
                return $this->session = $session;
            }
        }

        return null;
    }

    /**
     * @param SessionEntity|null $session
     */
    public function setSession(SessionEntity $session = null)
    {
        $this->session = $session;
    }
    
    public function createSession(UserEntityInterface $user, $isLongSession = false): SessionEntityInterface
    {
        $session = $this->container->get(SessionEntityInterface::class);
        $session->setUser($user);
        $session->setIsLongSession($isLongSession);

        /** @var SessionRepositoryInterface $sessionRepository */
        $sessionRepository = $this->container->get(SessionRepositoryInterface::class);
        $sessionRepository->save($session);

        return $this->session = $session;
    }

    /**
     * @return $this
     */
    public function deleteSession()
    {
        $session = $this->getSession();
        if ($session instanceof SessionEntity) {
            $this->getDoctrine()->getEntityManager()->remove($session);
            $this->getDoctrine()->getEntityManager()->flush($session);
        }
        $this->deleteSessionCookie();

        return $this;
    }

    /**
     * @param SessionEntity $session
     */
    public function createSessionCookie(SessionEntity $session = null)
    {
        if ($session === null) {
            $session = $this->session;
        }

        if ($session !== null) {
            $config = $this->getConfig();
            $cookie = new ResponseCookie(
                SessionEntity::COOKIE_NAME,
                $session->getId(),
                $session->getExpirationDatetime(),
                $config->get('cookie.path'),
                $config->get('cookie.domain'),
                $config->get('cookie.secure'),
                $config->get('cookie.httponly')
            );

            $this->getResponse()->cookies()->set(
                $cookie->getName(),
                $cookie
            );
        }
    }

    /**
     * @return $this
     */
    public function deleteSessionCookie()
    {
        $this->getResponse()->cookies()->set(SessionEntity::COOKIE_NAME, null);

        return $this;
    }

    /**
     * @return $this
     */
    public function extendSession()
    {
        $session = $this->getSession();
        if ($session !== null) {

            $session->createExpirationDatetime();
            $this->getEntityManager()->flush($this->session);

            $this->createSessionCookie($this->session);
        }

        return $this;
    }

    /**
     * @return SessionRepository
     */
    private function getSessionRepository()
    {
        return $this->getEntityManager()
            ->getRepository(SessionEntity::class);
    }
}
