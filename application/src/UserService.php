<?php
namespace APP;
use APP\UserRepository;
use APP\UserRepositoryInterface;
use APP\User;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $nome, string $email, string $birth, string $password, string $password_confirmation): bool
    {
        $user = new User($nome, $email, $birth, $password, $password_confirmation);
        return $this->userRepository->save($user);
    }
}