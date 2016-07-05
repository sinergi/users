<?php

namespace Sinergi\Users\Group;

use DateTime;

trait GroupEntityTrait
{
    protected $id;
    protected $creationDatetime;
    protected $modificationDatetime;

    public function __construct()
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
