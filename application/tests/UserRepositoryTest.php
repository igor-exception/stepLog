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

    public function testSaveThrowException()
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
            ])
            ->willThrowException(new \PDOException);

        $mockPdo = $this->createMock(\PDO::class);
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO users(name, email, birth, password) VALUES (:name, :email, :birth, :password);")
            ->willReturn($mockStatement);

        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage('Erro no banco.');
        
        $userRepository = new APP\UserRepository($mockPdo);
        $userRepository->save($user);
    }

    public function testFindAllUsers()
    {
        $listUsers = [
            [
                'user_id' => '1',
                'name' => 'John Doe',
                'email' => 'john@gmail.com',
                'birth' => '1990-01-01'
            ],
            [
                'user_id' => '2',
                'name' => 'Mary Doe',
                'email' => 'mary@gmail.com',
                'birth' => '1995-01-01'
            ]
        ];
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn($listUsers);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('query')
            ->with('SELECT user_id, name, email, birth FROM users')
            ->willReturn($mockStatement);
        
        $userRepository = new UserRepository($mockPdo);
        $users = $userRepository->findAll();
        $this->assertEquals($listUsers, $users);
    }

    public function testFindAllThrowException()
    {
        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage('Erro no banco.');
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->expects($this->once())
            ->method('query')
            ->with('SELECT user_id, name, email, birth FROM users')
            ->willThrowException(new PDOException);
        $userRepository = new UserRepository($mockPdo);
        $userRepository->findAll();
        
    }
}