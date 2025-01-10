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
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $data;
        }catch(\PDOException $e) {
            throw new RepositoryException("Erro no banco.", 0, $e);
        }
    }
}