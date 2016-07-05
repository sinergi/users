<?php

namespace Sinergi\Users\Group;

use DateTime;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

trait GroupEntityTrait
{
    protected $id;
    /** @var UserEntityInterface[] */
    protected $users;
    protected $creationDatetime;
    protected $modificationDatetime;
    /** @var UserRepositoryInterface */
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository = null)
    {
        $this->setCreationDatetime(new DateTime());
        $this->setModificationDatetime(new DateTime());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): GroupEntityInterface
    {
        $this->id = $id;
        return $this;
    }

    /** @return UserEntityInterface[] */
    public function getUsers()
    {
        if (is_array($this->users)) {
            return $this->users;
        } elseif (!$this->userRepository) {
            throw new \Exception('Cannot fetch users without user repository');
        }
        return $this->users = $this->userRepository->findByGroupId($this->getId());
    }

    public function getCreationDatetime(): DateTime
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(DateTime $creationDatetime): GroupEntityInterface
    {
        $this->creationDatetime = $creationDatetime;
        return $this;
    }

    public function getModificationDatetime(): DateTime
    {
        return $this->modificationDatetime;
    }

    public function setModificationDatetime(DateTime $modificationDatetime): GroupEntityInterface
    {
        $this->modificationDatetime = $modificationDatetime;
        return $this;
    }

    public function setUserRepository(UserRepositoryInterface $userRepository): GroupEntityInterface
    {
        $this->userRepository = $userRepository;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'creationDatetime' => $this->getCreationDatetime()->format('Y-m-d H:i:s'),
            'modificationDatetime' => $this->getModificationDatetime()->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
