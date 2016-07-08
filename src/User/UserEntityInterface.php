<?php

namespace Sinergi\Users\User;

use JsonSerializable;
use DateInterval;
use DateTime;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupRepositoryInterface;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;

interface UserEntityInterface extends JsonSerializable
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_BANNED = 'banned';

    const EMAIL_COOLDOWN = 300;

    /** @return int */
    public function getId();
    public function setId(int $id): UserEntityInterface;
    /** @return int|null */
    public function getGroupId();
    public function setGroupId(int $groupId = null): UserEntityInterface;
    /** @return GroupEntityInterface|null */
    public function getGroup();
    public function setGroup(GroupEntityInterface $group = null): UserEntityInterface;
    /** @return SessionEntityInterface[] */
    public function getSessions();
    /** @return string|null */
    public function getStatus();
    public function setStatus(string $status): UserEntityInterface;
    public function isAdmin(): bool;
    public function setIsAdmin(bool $isAdmin): UserEntityInterface;
    public function isActive(): bool;
    public function getEmail(): string;
    public function setEmail(string $email): UserEntityInterface;
    /** @return string */
    public function getPendingEmail();
    public function setPendingEmail(string $pendingEmail): UserEntityInterface;
    public function isEmailConfirmed(): bool;
    public function setIsEmailConfirmed(bool $isEmailConfirmed): UserEntityInterface;
    /** @return string */
    public function getEmailConfirmationToken();
    public function setEmailConfirmationToken(string $emailConfirmationToken = null): UserEntityInterface;
    /** @return int */
    public function getEmailConfirmationTokenAttempts();
    public function setEmailConfirmationTokenAttempts(int $emailConfirmationTokenAttempts = 0): UserEntityInterface;
    /** @return DateTime */
    public function getEmailConfirmationTokenExpirationDatetime();
    public function setEmailConfirmationTokenExpirationDatetime(
        DateTime $emailConfirmationTokenExpirationDatetime = null
    ): UserEntityInterface;
    /** @return DateTime */
    public function getLastEmailTokenGeneratedDatetime();
    public function setLastEmailTokenGeneratedDatetime(
        DateTime $lastEmailTokenGeneratedDatetime
    ): UserEntityInterface;
    public function hasEmailConfirmationTokenCooldownExpired(): bool;
    public function hasEmailConfirmationTokenExpired(): bool;
    public function hasTooManyEmailConfirmationTokenAttempts(): bool;
    public function generateEmailConfirmationToken($token = null, DateInterval $expiration = null): UserEntityInterface;
    /** @return string */
    public function getDeletedEmail();
    public function setDeletedEmail(string $deletedEmail): UserEntityInterface;
    /** @return string */
    public function getPassword();
    public function setPassword(string $password): UserEntityInterface;
    /** @return string */
    public function getPasswordResetToken();
    public function setPasswordResetToken(string $passwordResetToken): UserEntityInterface;
    /** @return DateTime */
    public function getPasswordResetTokenExpirationDatetime();
    public function setPasswordResetTokenExpirationDatetime(
        DateTime $passwordResetTokenExpirationDatetime
    ): UserEntityInterface;
    /** @return DateTime */
    public function getLastPasswordResetTokenGeneratedDatetime();
    public function setLastPasswordResetTokenGeneratedDatetime(
        DateTime $lastPasswordResetTokenGeneratedDatetime
    ): UserEntityInterface;
    public function hasPasswordResetTokenCooldownExpired(): bool;
    public function generatePasswordResetToken($token = null, DateInterval $expiration = null): UserEntityInterface;
    public function testPassword(string $password): bool;
    public function getCreationDatetime(): DateTime;
    public function setCreationDatetime(DateTime $creationDatetime): UserEntityInterface;
    public function getModificationDatetime(): DateTime;
    public function setModificationDatetime(DateTime $modificationDatetime): UserEntityInterface;
    public function setGroupRepository(GroupRepositoryInterface $groupRepository): UserEntityInterface;
    public function setSessionRepository(SessionRepositoryInterface $sessionRepository): UserEntityInterface;
    public function toArray(): array;
    public function jsonSerialize();
}
