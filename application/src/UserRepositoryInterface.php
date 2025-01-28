<?php

namespace APP;
use APP\User;

interface UserRepositoryInterface
{
    public function save(User $user): string|false;
    public function findAll(): array;
    public function findById(int $id): ?User;
}