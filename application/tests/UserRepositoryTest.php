<?php

use PHPUnit\Framework\TestCase;
use APP\User;
use APP\UserRepository;
use APP\Exceptions\RepositoryException;

class UserRepositoryTest extends TestCase
{
    public function testSaveWithValidData()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $user = new User($name, $email, $birth, $password);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'birth' => $user->getBirth(),
                'password' => $user->getPassword()
            ]);
        
        $mockPdo = $this->createMock(\PDO::class);
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO users(name, email, birth, password) VALUES (:name, :email, :birth, :password);")
            ->willReturn($mockStatement);

        $mockPdo->expects($this->once())
                ->method('lastInsertId')
                ->willReturn('1');

        $userRepository = new APP\UserRepository($mockPdo);
        $createdUserId = $userRepository->save($user);
        
        $this->assertEquals("1", $createdUserId);
    }

}