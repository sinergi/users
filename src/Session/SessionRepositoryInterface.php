<?php

namespace Sinergi\Users\Session;

interface SessionRepositoryInterface
{
    public function save(SessionEntityInterface $session);
    /** @return SessionEntityInterface */
    public function findById(string $id);
    /** @return SessionEntityInterface[] */
    public function findByUserId(int $id);
    public function delete(SessionEntityInterface $session);
}
