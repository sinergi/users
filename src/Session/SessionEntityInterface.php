<?php

namespace Sinergi\Users\Session;

use JsonSerializable;
use DateTime;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

interface SessionEntityInterface extends JsonSerializable
{
    const DEFAULT_COOKIE_NAME = 'user_session';
    const EXPIRATION_TIME = 'PT1H';
    const LONG_EXPIRATION_TIME = 'P1YT';

    public function isValid(): bool;
    public function setExpirationDatetime(DateTime $expirationDatetime): SessionEntityInterface;
    public function getExpirationDatetime(): DateTime;
    public function createExpirationDatetime();
    public function isExpired();
    public function setId(string $id): SessionEntityInterface;
    public function getId(): string;
    public function createId(): SessionEntityInterface;
    public function generateId(): string;
    public function setIsLongSession(bool $isLongSession): SessionEntityInterface;
    public function isLongSession(): bool;
    public function setUserId(int $userId): SessionEntityInterface;
    public function getUserId(): int;
    public function setUser(UserEntityInterface $user): SessionEntityInterface;
    /** @return UserEntityInterface */
    public function getUser();
    public function setUserRepository(UserRepositoryInterface $userRepository);
    public function toArray(): array;
    public function jsonSerialize();
}
