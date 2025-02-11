<?php
namespace APP;

use APP\UserRepositoryInterface;
use APP\User;
use APP\Exceptions\RepositoryException;

class UserRepository implements UserRepositoryInterface
{
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): string|false
    {
        try {
            $name = $user->getName();
            $email = $user->getEmail();
            $birth = $user->getBirth();
            $password = $user->getPassword();

            $stmt = $this->pdo->prepare("INSERT INTO users(name, email, birth, password) VALUES (:name, :email, :birth, :password);");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'birth' => $birth,
                'password' => $password
            ]);

            return $this->pdo->lastInsertId();
        }catch(\PDOException $e) {
            throw new RepositoryException("Erro no banco.", 0, $e);
        }
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT user_id, name, email, birth FROM users");
            $usersData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $users = [];
            foreach($usersData as $userData) {
                $users[] = User::hydrateWithoutPasswordHash((int)$userData['user_id'], $userData['name'], $userData['email'], $userData['birth']);
            }
            return $users;
        }catch(\PDOException $e) {
            throw new RepositoryException("Erro no banco.", 0, $e);
        }
    }

    public function findById(int $id): ?User
    {
        try {
            $stmt = $this->pdo->prepare("SELECT user_id, name, email, birth FROM users WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $id]);
            $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
            if(!$userData) {
                throw new RepositoryException("Não foi possível encontrar usuário", 0, $e);
            }
            
            $user = User::hydrateWithoutPasswordHash((int)$userData['user_id'], $userData['name'], $userData['email'], $userData['birth']);
            return $user;
        }catch(\PDOException $e) {
            throw new RepositoryException("Erro no banco.", 0, $e);
        }
    }

    public function update(User $user): ?User
    {
        try {
            $userId = $user->getId();
            $name = $user->getName();
            $email = $user->getEmail();
            $birth = $user->getBirth();

            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, birth = :birth WHERE user_id = :user_id;");
            $stmt->execute([
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
                'birth' => $birth
            ]);

            if($stmt->rowCount() <= 0) {
                return false;
            }
            return $this->findById($userId);
        }catch(\PDOException $e) {
            throw new RepositoryException("Erro no banco.", 0, $e);
        }
    }
    
}