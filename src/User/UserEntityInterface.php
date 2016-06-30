<?php

namespace Sinergi\Users\User;

use JsonSerializable;
use DateTime;

interface UserEntityInterface extends JsonSerializable
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_BANNED = 'banned';

    const EMAIL_COOLDOWN = 300;

    public function getId(): int;
    public function setId(int $id): UserEntityInterface;
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
    public function setEmailConfirmationToken(string $emailConfirmationToken): UserEntityInterface;
    /** @return DateTime */
    public function getEmailConfirmationTokenExpirationDatetime();
    public function setEmailConfirmationTokenExpirationDatetime(
        DateTime $emailConfirmationTokenExpirationDatetime
    ): UserEntityInterface;
    /** @return DateTime */
    public function getLastEmailTokenGeneratedDatetime();
    public function setLastEmailTokenGeneratedDatetime(
        DateTime $lastEmailTokenGeneratedDatetime
    ): UserEntityInterface;
    public function canGenerateNewEmailConfirmationToken(): bool;
    public function generateEmailConfirmationToken(): UserEntityInterface;
    /** @return string */
    public function getDeletedEmail();
    public function setDeletedEmail(string $deletedEmail): UserEntityInterface;
    public function getPassword(): string;
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
    public function canGenerateNewResetPasswordToken(): bool;
    public function generatePasswordResetToken(): UserEntityInterface;
    public function testPassword(string $password): bool;
    public function getCreationDatetime(): DateTime;
    public function setCreationDatetime(DateTime $creationDatetime): UserEntityInterface;
    public function getModificationDatetime(): DateTime;
    public function setModificationDatetime(DateTime $modificationDatetime): UserEntityInterface;
    public function toArray(): array;
    public function jsonSerialize();
}
