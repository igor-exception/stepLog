<?php

namespace APP;
use APP\User;

interface UserRepositoryInterface
{
    public function save(User $user): string|false;
}