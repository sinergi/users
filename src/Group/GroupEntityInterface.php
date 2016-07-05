<?php

namespace Sinergi\Users\Group;

use JsonSerializable;
use DateTime;

interface GroupEntityInterface extends JsonSerializable
{
    public function getId(): int;
    public function setId(int $id): GroupEntityInterface;
    public function getCreationDatetime(): DateTime;
    public function setCreationDatetime(DateTime $creationDatetime): GroupEntityInterface;
    public function getModificationDatetime(): DateTime;
    public function setModificationDatetime(DateTime $modificationDatetime): GroupEntityInterface;
    public function toArray(): array;
    public function jsonSerialize();
}
