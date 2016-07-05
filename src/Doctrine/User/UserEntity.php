<?php

namespace Sinergi\Users\Doctrine\User;

use Doctrine\ORM\Mapping as ORM;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserEntityTrait;

/**
 * @ORM\Entity(repositoryClass="Sinergi\Users\Doctrine\User\UserRepository")
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique_idx", columns={"email"})})
 */
class UserEntity implements UserEntityInterface
{
    use UserEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="group_id")
     */
    protected $groupId;

    /**
     * @ORM\Column(type="string", name="status", columnDefinition="ENUM('active','deleted','banned')")
     */
    protected $status;

    /**
     * @ORM\Column(type="boolean", name="is_admin")
     */
    protected $isAdmin = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email = null;

    /**
     * @ORM\Column(type="string", name="pending_email", length=255, nullable=true)
     */
    protected $pendingEmail = null;

    /**
     * @ORM\Column(type="string", name="deleted_email", length=255, nullable=true)
     */
    protected $deletedEmail = null;

    /**
     * @ORM\Column(type="boolean", name="is_email_confirmed")
     */
    protected $isEmailConfirmed = false;

    /**
     * @ORM\Column(type="string", name="email_confirmation_token", length=40, nullable=true)
     */
    protected $emailConfirmationToken;

    /**
     * @ORM\Column(type="datetime", name="email_confirmation_token_expiration_datetime", nullable=true)
     */
    protected $emailConfirmationTokenExpirationDatetime;

    /**
     * @ORM\Column(type="datetime", name="last_email_token_generated_datetime", nullable=true)
     */
    protected $lastEmailTokenGeneratedDatetime;
    
    /**
     * @ORM\Column(type="string", length=60, columnDefinition="BINARY(60)")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=40, name="password_reset_token", nullable=true)
     */
    protected $passwordResetToken;

    /**
     * @ORM\Column(type="datetime", name="password_token_expiration_datetime", nullable=true)
     */
    protected $passwordResetTokenExpirationDatetime;

    /**
     * @ORM\Column(type="datetime", name="last_password_reset_token_generated_datetime", nullable=true)
     */
    protected $lastPasswordResetTokenGeneratedDatetime;

    /**
     * @ORM\Column(type="datetime", name="creation_datetime")
     */
    protected $creationDatetime;

    /**
     * @ORM\Column(type="datetime", name="modification_datetime")
     */
    protected $modificationDatetime;
}
