<?php

namespace Sinergi\Users\Group;

use JsonSerializable;
use DateTime;
use Sinergi\Users\User\UserEntityInterface;
use Sinergi\Users\User\UserRepositoryInterface;

interface GroupEntityInterface extends JsonSerializable
{
    public function getId(): int;
    public function setId(int $id): GroupEntityInterface;
    /** @return UserEntityInterface[] */
    public function getUsers();
    public function getCreationDatetime(): DateTime;
    public function setCreationDatetime(DateTime $creationDatetime): GroupEntityInterface;
    public function getModificationDatetime(): DateTime;
    public function setModificationDatetime(DateTime $modificationDatetime): GroupEntityInterface;
    public function setUserRepository(UserRepositoryInterface $userRepository): GroupEntityInterface;
    public function toArray(): array;
    public function jsonSerialize();
}
