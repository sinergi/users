<?php

namespace Sinergi\Users\User;

use DateTime;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupRepositoryInterface;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionRepositoryInterface;
use Sinergi\Users\Utils\Token;
use DateInterval;

trait UserEntityTrait
{
    protected $id;
    protected $groupId;
    /** @var GroupEntityInterface */
    protected $group;
    /** @var SessionEntityInterface[] */
    protected $sessions;
    protected $status;
    protected $isAdmin;
    protected $email = null;
    protected $pendingEmail = null;
    protected $deletedEmail = null;
    protected $isEmailConfirmed = false;
    protected $emailConfirmationToken;
    protected $emailConfirmationTokenAttempts;
    protected $emailConfirmationTokenExpirationDatetime;
    protected $lastEmailTokenGeneratedDatetime;
    protected $password;
    protected $passwordResetToken;
    protected $passwordResetTokenExpirationDatetime;
    protected $lastPasswordResetTokenGeneratedDatetime;
    protected $creationDatetime;
    protected $modificationDatetime;
    /** @var GroupRepositoryInterface */
    protected $groupRepository;
    /** @var SessionRepositoryInterface */
    protected $sessionRepository;

    public function __construct(
        GroupRepositoryInterface $groupRepository = null,
        SessionRepositoryInterface $sessionRepository = null
    ) {
        $this->groupRepository = $groupRepository;
        $this->sessionRepository = $sessionRepository;
        $this->setStatus(UserEntityInterface::STATUS_ACTIVE);
        $this->setCreationDatetime(new DateTime());
        $this->setModificationDatetime(new DateTime());
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): UserEntityInterface
    {
        $this->id = $id;
        return $this;
    }

    /** @return int|null */
    public function getGroupId()
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId = null): UserEntityInterface
    {
        $this->groupId = $groupId;
        return $this;
    }

    /** @return GroupEntityInterface|null */
    public function getGroup()
    {
        if ($this->group && $this->group->getId() === $this->groupId) {
            return $this->group;
        } elseif (!$this->groupRepository) {
            throw new \Exception('Cannot fetch group without group repository');
        }
        return $this->group = $this->groupRepository->findById($this->getGroupId());
    }

    public function setGroup(GroupEntityInterface $group = null): UserEntityInterface
    {
        $this->setGroupId($group->getId());
        $this->group = $group;
        return $this;
    }

    /** @return SessionEntityInterface[] */
    public function getSessions()
    {
        if (is_array($this->sessions)) {
            return $this->sessions;
        } elseif (!$this->sessionRepository) {
            throw new \Exception('Cannot fetch sessions without session repository');
        }
        return $this->sessions = $this->sessionRepository->findByUserId($this->getId());
    }

    /** @return string|null */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(string $status): UserEntityInterface
    {
        $this->status = $status;
        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): UserEntityInterface
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->getStatus() === UserEntityInterface::STATUS_ACTIVE;
    }

    public function getEmail(): string
    {
        return $this->email ?: '';
    }

    public function setEmail(string $email): UserEntityInterface
    {
        $this->email = $email;
        return $this;
    }

    /** @return string */
    public function getPendingEmail()
    {
        return $this->pendingEmail;
    }

    public function setPendingEmail(string $pendingEmail): UserEntityInterface
    {
        $this->pendingEmail = $pendingEmail;
        return $this;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->isEmailConfirmed;
    }

    public function setIsEmailConfirmed(bool $isEmailConfirmed): UserEntityInterface
    {
        $this->isEmailConfirmed = $isEmailConfirmed;
        return $this;
    }

    /** @return string */
    public function getEmailConfirmationToken()
    {
        return $this->emailConfirmationToken;
    }

    public function setEmailConfirmationToken(string $emailConfirmationToken = null): UserEntityInterface
    {
        $this->emailConfirmationToken = $emailConfirmationToken;
        return $this;
    }

    /** @return int */
    public function getEmailConfirmationTokenAttempts()
    {
        return $this->emailConfirmationTokenAttempts;
    }

    public function setEmailConfirmationTokenAttempts(int $emailConfirmationTokenAttempts = 0): UserEntityInterface
    {
        $this->emailConfirmationTokenAttempts = $emailConfirmationTokenAttempts;
        return $this;
    }

    /** @return DateTime */
    public function getEmailConfirmationTokenExpirationDatetime()
    {
        return $this->emailConfirmationTokenExpirationDatetime;
    }

    public function setEmailConfirmationTokenExpirationDatetime(
        DateTime $emailConfirmationTokenExpirationDatetime = null
    ): UserEntityInterface {
        $this->emailConfirmationTokenExpirationDatetime = $emailConfirmationTokenExpirationDatetime;
        return $this;
    }

    /** @return DateTime */
    public function getLastEmailTokenGeneratedDatetime()
    {
        return $this->lastEmailTokenGeneratedDatetime;
    }

    public function setLastEmailTokenGeneratedDatetime(
        DateTime $lastEmailTokenGeneratedDatetime
    ): UserEntityInterface {
        $this->lastEmailTokenGeneratedDatetime = $lastEmailTokenGeneratedDatetime;
        return $this;
    }

    public function hasEmailConfirmationTokenCooldownExpired(): bool
    {
        return (new DateTime())->getTimestamp() - $this->getLastEmailTokenGeneratedDatetime()->getTimestamp() >
            UserEntityInterface::EMAIL_COOLDOWN;
    }

    public function hasEmailConfirmationTokenExpired(): bool
    {
        return ($this->getEmailConfirmationTokenExpirationDatetime() <= new DateTime());
    }

    public function hasTooManyEmailConfirmationTokenAttempts(): bool
    {
        return $this->getEmailConfirmationTokenAttempts() > 5;
    }

    public function generateEmailConfirmationToken($token = null, DateInterval $expiration = null): UserEntityInterface
    {
        if (null === $expiration) {
            $expiration = new DateInterval('P1D');
        }
        $this->setEmailConfirmationTokenAttempts(0);
        $this->setEmailConfirmationToken(null === $token ? Token::generate(40) : $token);
        $this->setEmailConfirmationTokenExpirationDatetime((new DateTime())->add($expiration));
        $this->setLastEmailTokenGeneratedDatetime(new DateTime());
        return $this;
    }

    /** @return string */
    public function getDeletedEmail()
    {
        return $this->deletedEmail;
    }

    public function setDeletedEmail(string $deletedEmail): UserEntityInterface
    {
        $this->deletedEmail = $deletedEmail;
        return $this;
    }

    /** @return string */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): UserEntityInterface
    {
        $this->password = $password;
        $this->hashPassword();
        return $this;
    }

    /** @return string */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(string $passwordResetToken = null): UserEntityInterface
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }

    /** @return DateTime */
    public function getPasswordResetTokenExpirationDatetime()
    {
        return $this->passwordResetTokenExpirationDatetime;
    }

    public function setPasswordResetTokenExpirationDatetime(
        DateTime $passwordResetTokenExpirationDatetime = null
    ): UserEntityInterface {
        $this->passwordResetTokenExpirationDatetime = $passwordResetTokenExpirationDatetime;
        return $this;
    }

    /** @return DateTime */
    public function getLastPasswordResetTokenGeneratedDatetime()
    {
        return $this->lastPasswordResetTokenGeneratedDatetime;
    }

    public function setLastPasswordResetTokenGeneratedDatetime(
        DateTime $lastPasswordResetTokenGeneratedDatetime
    ): UserEntityInterface {
        $this->lastPasswordResetTokenGeneratedDatetime = $lastPasswordResetTokenGeneratedDatetime;
        return $this;
    }

    public function hasPasswordResetTokenExpired(): bool
    {
        return ($this->getPasswordResetTokenExpirationDatetime() <= new DateTime());
    }

    public function hasPasswordResetTokenCooldownExpired(): bool
    {
        return (new DateTime())->getTimestamp() - $this->getLastPasswordResetTokenGeneratedDatetime()->getTimestamp() >
            UserEntityInterface::EMAIL_COOLDOWN;
    }

    public function generatePasswordResetToken($token = null, DateInterval $expiration = null): UserEntityInterface
    {
        if (null === $expiration) {
            $expiration = new DateInterval('P1D');
        }

        $this->setPasswordResetToken(null === $token ? Token::generate(40) : $token);
        $this->setPasswordResetTokenExpirationDatetime((new DateTime())->add($expiration));
        $this->setLastPasswordResetTokenGeneratedDatetime(new DateTime());
        return $this;
    }

    protected function hashPassword(): UserEntityInterface
    {
        $this->password = password_hash(
            (string) $this->password,
            PASSWORD_DEFAULT
        );
        return $this;
    }

    public function testPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function getCreationDatetime(): DateTime
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(DateTime $creationDatetime): UserEntityInterface
    {
        $this->creationDatetime = $creationDatetime;
        return $this;
    }

    public function getModificationDatetime(): DateTime
    {
        return $this->modificationDatetime;
    }

    public function setModificationDatetime(DateTime $modificationDatetime): UserEntityInterface
    {
        $this->modificationDatetime = $modificationDatetime;
        return $this;
    }

    public function setGroupRepository(GroupRepositoryInterface $groupRepository): UserEntityInterface
    {
        $this->groupRepository = $groupRepository;
        return $this;
    }

    public function setSessionRepository(SessionRepositoryInterface $sessionRepository): UserEntityInterface
    {
        $this->sessionRepository = $sessionRepository;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'groupId' => $this->getGroupId(),
            'status' => $this->getStatus(),
            'isAdmin' => $this->isAdmin(),
            'email' => $this->getEmail(),
            'pendingEmail' => $this->getPendingEmail(),
            'deletedEmail' => $this->getDeletedEmail(),
            'isEmailConfirmed' => $this->isEmailConfirmed(),
            'emailConfirmationToken' => $this->getEmailConfirmationToken(),
            'emailConfirmationTokenExpirationDatetime' => $this->getEmailConfirmationTokenExpirationDatetime() ?
                $this->getEmailConfirmationTokenExpirationDatetime()->format('Y-m-d H:i:s') : null,
            'lastEmailTokenGeneratedDatetime' => $this->getLastEmailTokenGeneratedDatetime() ?
                $this->getLastEmailTokenGeneratedDatetime()->format('Y-m-d H:i:s') : null,
            'password' => $this->getPassword(),
            'passwordResetToken' => $this->getPasswordResetToken(),
            'passwordResetTokenExpirationDatetime' => $this->getPasswordResetTokenExpirationDatetime() ?
                $this->getPasswordResetTokenExpirationDatetime()->format('Y-m-d H:i:s') : null,
            'lastPasswordResetTokenGeneratedDatetime' => $this->getLastPasswordResetTokenGeneratedDatetime() ?
                $this->getLastPasswordResetTokenGeneratedDatetime()->format('Y-m-d H:i:s') : null,
            'creationDatetime' => $this->getCreationDatetime()->format('Y-m-d H:i:s'),
            'modificationDatetime' => $this->getModificationDatetime()->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize()
    {
        return array_intersect_key(
            $this->toArray(),
            [
                'id' => null,
                'groupId' => null,
                'status' => null,
                'isAdmin' => null,
                'email' => null,
                'isEmailConfirmed' => null,
                'creationDatetime' => null,
                'modificationDatetime' => null,
            ]
        );
    }
}
