<?php

namespace Sinergi\Users\Eloquent\User;

use Illuminate\Database\Eloquent\Model;
use Sinergi\Users\Eloquent\Group\GroupEntity;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserEntityTrait;
use DateTime;

class UserEntity extends Model implements UserEntityInterface
{
    const CREATED_AT = 'creation_datetime';
    const UPDATED_AT = 'modification_datetime';

    use UserEntityTrait;

    public $id;
    public $groupId;
    public $status = UserEntityInterface::STATUS_ACTIVE;
    public $isAdmin;
    public $email = null;
    public $pendingEmail = null;
    public $deletedEmail = null;
    public $isEmailConfirmed = false;
    public $emailConfirmationToken;
    public $emailConfirmationTokenExpirationDatetime;
    public $lastEmailTokenGeneratedDatetime;
    public $password;
    public $passwordResetToken;
    public $passwordResetTokenExpirationDatetime;
    public $lastPasswordResetTokenGeneratedDatetime;
    public $creationDatetime;
    public $modificationDatetime;

    protected $table = 'users';

    protected $dates = [
        'email_confirmation_token_expiration_datetime',
        'last_email_token_generated_datetime',
        'password_reset_token_expiration_datetime',
        'last_password_reset_token_generated_datetime',
        'creation_datetime',
        'modification_datetime',
    ];

    protected $casts = [
        'id' => 'int',
        'group_id' => 'int',
        'status' => 'string',
        'is_admin' => 'boolean',
        'email' => 'string',
        'pending_email' => 'string',
        'is_email_confirmed' => 'boolean',
        'email_confirmation_token' => 'string',
        'deleted_email' => 'string',
        'password' => 'string',
        'password_reset_token' => 'string',
    ];

    public function group()
    {
        return $this->hasOne(GroupEntity::class);
    }

    public function getIdAttribute(): int
    {
        return $this->getId();
    }

    public function setIdAttribute(int $id)
    {
        $this->setId($id);
    }

    public function getGroup(): GroupEntity
    {
        return $this->group();
    }

    public function getStatusAttribute(): string
    {
        return $this->getStatus();
    }

    public function setStatusAttribute(string $status)
    {
        $this->setStatus($status);
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    public function setIsAdminAttribute(bool $isAdmin)
    {
        $this->setIsAdmin($isAdmin);
    }

    public function getEmailAttribute(): string
    {
        return $this->getEmail();
    }

    public function setEmailAttribute(string $email)
    {
        $this->setEmail($email);
    }

    public function getPendingEmailAttribute(): string
    {
        return $this->getPendingEmail();
    }

    public function setPendingEmailAttribute(string $pendingEmail)
    {
        $this->setPendingEmail($pendingEmail);
    }

    public function getIsEmailConfirmedAttribute(): bool
    {
        return $this->isEmailConfirmed();
    }

    public function setIsEmailConfirmedAttribute(bool $isEmailConfirmed)
    {
        $this->setIsEmailConfirmed($isEmailConfirmed);
    }

    public function getEmailConfirmationTokenAttribute(): string
    {
        return $this->getEmailConfirmationToken();
    }

    public function setEmailConfirmationTokenAttribute(string $emailConfirmationToken)
    {
        $this->setEmailConfirmationToken($emailConfirmationToken);
    }

    public function getEmailConfirmationTokenExpirationDatetimeAttribute(): DateTime
    {
        return $this->getEmailConfirmationTokenExpirationDatetime();
    }

    public function setEmailConfirmationTokenExpirationDatetimeAttribute(
        DateTime $emailConfirmationTokenExpirationDatetime
    ) {
        $this->setEmailConfirmationTokenExpirationDatetime($emailConfirmationTokenExpirationDatetime);
    }

    public function getLastEmailTokenGeneratedDatetimeAttribute(): DateTime
    {
        return $this->getLastEmailTokenGeneratedDatetime();
    }

    public function setLastEmailTokenGeneratedDatetimeAttribute(
        DateTime $lastEmailTokenGeneratedDatetime
    ) {
        $this->setLastEmailTokenGeneratedDatetime($lastEmailTokenGeneratedDatetime);
    }

    public function getDeletedEmailAttribute(): string
    {
        return $this->getDeletedEmail();
    }

    public function setDeletedEmailAttribute(string $deletedEmail)
    {
        $this->setDeletedEmail($deletedEmail);
    }

    public function getPasswordAttribute(): string
    {
        return $this->getPassword();
    }

    public function setPasswordAttribute(string $password)
    {
        $this->setPassword($password);
    }

    public function getPasswordResetTokenAttribute(): string
    {
        return $this->getPasswordResetToken();
    }

    public function setPasswordResetTokenAttribute(string $passwordResetToken)
    {
        $this->setPasswordResetToken($passwordResetToken);
    }

    public function getPasswordResetTokenExpirationDatetimeAttribute(): DateTime
    {
        return $this->getPasswordResetTokenExpirationDatetime();
    }

    public function setPasswordResetTokenExpirationDatetimeAttribute(
        DateTime $passwordResetTokenExpirationDatetime
    ) {
        $this->setPasswordResetTokenExpirationDatetime($passwordResetTokenExpirationDatetime);
    }

    public function getLastPasswordResetTokenGeneratedDatetimeAttribute(): DateTime
    {
        return $this->getLastPasswordResetTokenGeneratedDatetime();
    }

    public function setLastPasswordResetTokenGeneratedDatetimeAttribute(
        DateTime $lastPasswordResetTokenGeneratedDatetime
    ) {
        $this->setLastPasswordResetTokenGeneratedDatetime($lastPasswordResetTokenGeneratedDatetime);
    }

    public function getCreationDatetimeAttribute(): DateTime
    {
        return $this->getCreationDatetime();
    }

    public function setCreationDatetimeAttribute(DateTime $creationDatetime)
    {
        $this->setCreationDatetime($creationDatetime);
    }

    public function getModificationDatetimeAttribute(): DateTime
    {
        return $this->getModificationDatetime();
    }

    public function setModificationDatetimeAttribute(DateTime $modificationDatetime)
    {
        $this->setModificationDatetime($modificationDatetime);
    }
}
