<?php
namespace APP;

use APP\UserRepositoryInterface;
use APP\User;

class UserRepository implements UserRepositoryInterface
{
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): bool
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $birth = $user->getBirth();
        $password = $user->getPassword();

        /*
            1- criar query para insercao
            2- 
        */

        $stmt = $this->pdo->prepare("INSERT INTO users(name, email, birth, password) VALUES (:name, :email, :birth, :password);");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'birth' => $birth,
            'password' => $password
        ]);

        if($stmt->rowCount() <= 0){
            return false;
        }
        return true;
    }
}