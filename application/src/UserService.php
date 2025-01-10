<?php
namespace APP;
use APP\UserRepository;
use APP\UserRepositoryInterface;
use APP\Exceptions\RepositoryException;
use APP\Exceptions\ServiceException;
use APP\User;

class UserService
{
    private $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(string $name, string $email, string $birth, string $password): int
    {
        try {
            $user = new User($name, $email, $birth, $password);
            return $this->userRepository->save($user);
        }catch (RepositoryException $e) {
            throw new ServiceException("Erro ao registrar usuário: ". $e->getMessage(), 0, $e);
        }
    }

    public function getAllUsers(): array
    {
        try {
            return $this->userRepository->findAll();
        }catch (RepositoryException $e) {
            throw new ServiceException("Erro ao consultar usuário: ". $e->getMessage(), 0, $e);
        }
    }
}