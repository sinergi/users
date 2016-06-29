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
    public function getStatus(): string;
    public function setStatus(string $status): UserEntityInterface;
    public function isAdmin(): boolean;
    public function setIsAdmin(boolean $isAdmin): UserEntityInterface;
    public function isActive(): boolean;
    public function getEmail(): string;
    public function setEmail(string $email): UserEntityInterface;
    public function getPendingEmail(): string;
    public function setPendingEmail(string $pendingEmail): UserEntityInterface;
    public function isEmailConfirmed(): boolean;
    public function setIsEmailConfirmed(boolean $isEmailConfirmed): UserEntityInterface;
    public function getEmailConfirmationToken(): string;
    public function setEmailConfirmationToken(string $emailConfirmationToken): UserEntityInterface;
    public function getEmailConfirmationTokenExpirationDatetime(): DateTime;
    public function setEmailConfirmationTokenExpirationDatetime(
        DateTime $emailConfirmationTokenExpirationDatetime
    ): UserEntityInterface;
    public function getLastEmailTokenGeneratedDatetime(): DateTime;
    public function setLastEmailTokenGeneratedDatetime(
        DateTime $lastEmailTokenGeneratedDatetime
    ): UserEntityInterface;
    public function canGenerateNewEmailConfirmationToken(): boolean;
    public function generateEmailConfirmationToken(): UserEntityInterface;
    public function getDeletedEmail(): string;
    public function setDeletedEmail(string $deletedEmail): UserEntityInterface;
    public function getPassword(): string;
    public function setPassword(string $password): UserEntityInterface;
    public function getPasswordResetToken(): string;
    public function setPasswordResetToken(string $passwordResetToken): UserEntityInterface;
    public function getPasswordResetTokenExpirationDatetime(): DateTime;
    public function setPasswordResetTokenExpirationDatetime(
        DateTime $passwordResetTokenExpirationDatetime
    ): UserEntityInterface;
    public function getLastPasswordResetTokenGeneratedDatetime(): DateTime;
    public function setLastPasswordResetTokenGeneratedDatetime(
        DateTime $lastPasswordResetTokenGeneratedDatetime
    ): UserEntityInterface;
    public function canGenerateNewResetPasswordToken(): boolean;
    public function generatePasswordResetToken(): UserEntityInterface;
    public function testPassword(string $password): boolean;
    public function getCreationDatetime(): DateTime;
    public function setCreationDatetime(DateTime $creationDatetime): UserEntityInterface;
    public function getModificationDatetime(): DateTime;
    public function setModificationDatetime(DateTime $modificationDatetime): UserEntityInterface;
    public function toArray(): array;
    public function jsonSerialize(): array;
}
