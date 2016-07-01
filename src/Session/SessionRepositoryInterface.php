<?php

namespace Sinergi\Users\Session;

interface SessionRepositoryInterface
{
    public function save(SessionEntityInterface $session);
    /** @return SessionEntityInterface */
    public function findById(string $id);
    public function delete(SessionEntityInterface $session);
}
