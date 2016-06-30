<?php

namespace Sinergi\Users\Eloquent\Session;

use Illuminate\Database\Eloquent\Model;
use Sinergi\Users\Eloquent\User\UserEntity;
use Sinergi\Users\Session\SessionEntityInterface;
use Sinergi\Users\Session\SessionEntityTrait;
use DateTime;

class SessionEntity extends Model implements SessionEntityInterface
{
    const CREATED_AT = 'expiration_datetime';

    use SessionEntityTrait;

    public $id;
    public $user_id;
    public $isLongSession = false;
    public $expirationDatetime;

    protected $table = 'sessions';

    protected $dates = [
        'expiration_datetime'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'int',
        'is_long_session' => 'boolean'
    ];

    public function user()
    {
        return $this->hasOne(UserEntity::class);
    }

    public function getIdAttribute(): string
    {
        return $this->getId();
    }

    public function setIdAttribute(string $id)
    {
        $this->setId($id);
    }

    public function getUser(): UserEntity
    {
        return $this->user();
    }

    public function setIsLongSessionAttribute(bool $isLongSession)
    {
        $this->setIsLongSession($isLongSession);
    }

    public function getIsLongSessionAttribute(): bool
    {
        return $this->isLongSession();
    }

    public function getExpirationDatetimeAttribute(): DateTime
    {
        return $this->getExpirationDatetime();
    }

    public function setExpirationDatetimeAttribute(DateTime $expirationDatetime)
    {
        $this->setExpirationDatetime($expirationDatetime);
    }
}
