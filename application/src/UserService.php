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
            $user = User::create($name, $email, $birth, $password);
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
            throw new ServiceException("Erro ao consultar usuário", 0, $e);
        }
    }

    public function getUserById(int $id): ?User
    {
        try {
            $user = $this->userRepository->findById($id);
            if(!$user) {
                throw new ServiceException('Usuário não encontrado', 0);
            }
            return $user;
        }catch (RepositoryException $e) {
            throw new ServiceException("Erro ao consultar usuário pelo ID: ". $e->getMessage(), 0, $e);
        }
    }

    public function update(int $id, string $name, string $email, string $birth): ?User
    {
        try{
            $userUpdate = User::updateWithoutPasswordHash($id, $name, $email, $birth);
            $user = $this->userRepository->update($userUpdate);
            if(!$user) {
                throw new ServiceException('Erro ao atualizar usuário', 0);
            }
            return $user;
        }catch (RepositoryException $e) {
            throw new ServiceException("Erro ao atualizar usuário:". $e->getMessage(), 0, $e);
        }
    }
}