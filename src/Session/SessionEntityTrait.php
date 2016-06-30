<?php

namespace Sinergi\Users\Session;

use DateTime;
use DateInterval;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\Utils\Token;

trait SessionEntityTrait
{
    protected $id;
    protected $user;
    protected $isLongSession = false;
    protected $expirationDatetime;

    public function __construct()
    {
        $this->createId();
        $this->createExpirationDatetime();
    }

    public function isValid(): bool
    {
        return !$this->isExpired() && $this->getUser() instanceof UserEntityInterface;
    }

    public function setExpirationDatetime(DateTime $expirationDatetime): SessionEntityInterface
    {
        $this->expirationDatetime = $expirationDatetime;
        return $this;
    }

    public function getExpirationDatetime(): DateTime
    {
        return $this->expirationDatetime;
    }

    public function createExpirationDatetime()
    {
        $expirationTime = $this->isLongSession() ?
            SessionEntityInterface::LONG_EXPIRATION_TIME : SessionEntityInterface::EXPIRATION_TIME;
        $expiration = (new DateTime)->add(new DateInterval($expirationTime));
        return $this->setExpirationDatetime($expiration);
    }

    public function isExpired()
    {
        return $this->getExpirationDatetime() < new DateTime;
    }

    public function setId(string $id): SessionEntityInterface
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function createId(): SessionEntityInterface
    {
        return $this->setId($this->generateId());
    }

    public function generateId(): string
    {
        return Token::generate(128);
    }

    public function setIsLongSession(bool $isLongSession): SessionEntityInterface
    {
        $this->isLongSession = $isLongSession;
        return $this;
    }

    public function isLongSession(): bool
    {
        return $this->isLongSession;
    }

    public function setUser(UserEntityInterface $user): SessionEntityInterface
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): UserEntityInterface
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser()->toArray(),
            'isLongSession' => $this->isLongSession(),
            'expirationDatetime' => $this->getExpirationDatetime()->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
