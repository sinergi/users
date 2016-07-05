<?php

namespace Sinergi\Users\Eloquent\Group;

use Illuminate\Database\Eloquent\Model;
use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupEntityTrait;
use DateTime;

class GroupEntity extends Model implements GroupEntityInterface
{
    const CREATED_AT = 'creation_datetime';
    const UPDATED_AT = 'modification_datetime';

    use GroupEntityTrait;

    public $id;
    public $creationDatetime;
    public $modificationDatetime;

    protected $table = 'groups';

    protected $dates = [
        'creation_datetime',
        'modification_datetime',
    ];

    protected $casts = [
        'id' => 'int'
    ];

    public function getIdAttribute(): int
    {
        return $this->getId();
    }

    public function setIdAttribute(int $id)
    {
        $this->setId($id);
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
