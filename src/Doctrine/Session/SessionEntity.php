<?php

namespace Sinergi\Users\Doctrine\Session;

use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sinergi\Users\Doctrine\Session\SessionRepository")
 * @ORM\Table(name="sessions")
 */
class SessionEntity implements SessionEntityInterface
{
    use SessionEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=128, columnDefinition="BINARY(128)"))
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="user_id")
     */
    protected $userId;

    /**
     * @ORM\Column(type="boolean", name="is_long_session")
     */
    protected $isLongSession = false;

    /**
     * @ORM\Column(type="datetime", name="expiration_datetime")
     */
    protected $expirationDatetime;
}
