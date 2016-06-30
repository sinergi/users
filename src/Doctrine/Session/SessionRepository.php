<?php

namespace Sinergi\Users\Doctrine\Session;

use Doctrine\ORM\EntityRepository;
use Sinergi\Users\Session\SessionRepositoryInterface;

class SessionRepository extends EntityRepository implements SessionRepositoryInterface
{
}
