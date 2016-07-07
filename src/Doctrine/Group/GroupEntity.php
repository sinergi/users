<?php

namespace Sinergi\Users\Doctrine\Group;

use Sinergi\Users\Group\GroupEntityInterface;
use Sinergi\Users\Group\GroupEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="groups")
 */
class GroupEntity implements GroupEntityInterface
{
    use GroupEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", name="creation_datetime")
     */
    protected $creationDatetime;

    /**
     * @ORM\Column(type="datetime", name="modification_datetime")
     */
    protected $modificationDatetime;
}
