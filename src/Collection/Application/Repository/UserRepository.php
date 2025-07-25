<?php

namespace App\Collection\Application\Repository;

use App\Collection\Domain\User;

interface UserRepository
{
    public function findCurrent(): ?User;
}