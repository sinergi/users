<?php

namespace Sinergi\Users\Session;

interface SessionRepositoryInterface
{
    public function save(SessionEntityInterface $session);
}
