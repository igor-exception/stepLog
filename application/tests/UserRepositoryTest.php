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
        $user = User::create($name, $email, $birth, $password);

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
        $user = User::create($name, $email, $birth, $password);

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
        $user1 = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1990-01-01');
        $user2 = User::hydrateWithoutPasswordHash(2, 'Mary Doe', 'mary@gmail.com', '1995-01-01');
        $listUsersObj = [
            $user1,
            $user2
        ];

        $listUsersArray = [
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
            ->willReturn($listUsersArray);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('query')
            ->with('SELECT user_id, name, email, birth FROM users')
            ->willReturn($mockStatement);
        
        $userRepository = new UserRepository($mockPdo);
        $users = $userRepository->findAll();
        $this->assertEquals($listUsersObj, $users);
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

    public function testFindById()
    {
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1990-01-01');

        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn(['user_id' => 1, 'name' => 'John Doe', 'email' => 'john@gmail.com', 'birth' => '1990-01-01']);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with(['user_id' => 1]);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')
            ->with("SELECT user_id, name, email, birth FROM users WHERE user_id = :user_id")
            ->willReturn($mockStatement);
        
        $userRepository = new UserRepository($mockPdo);
        $ReturnedUser = $userRepository->findById(1);
        $this->assertEquals($user, $ReturnedUser);
    }

    public function testFindByIdThrowException()
    {
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1990-01-01');

        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn(null);
        
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with(['user_id' => 1]);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')
            ->with("SELECT user_id, name, email, birth FROM users WHERE user_id = :user_id")
            ->willReturn($mockStatement);
        
        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage("Não foi possível encontrar usuário");

        $userRepository = new UserRepository($mockPdo);
        $ReturnedUser = $userRepository->findById(1);
    }

    public function testFindByIdPdoException()
    {
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1990-01-01');

        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->willThrowException(new PDOException());
        
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with(['user_id' => 1]);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')
            ->with("SELECT user_id, name, email, birth FROM users WHERE user_id = :user_id")
            ->willReturn($mockStatement);
        
        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage("Erro no banco");

        $userRepository = new UserRepository($mockPdo);
        $ReturnedUser = $userRepository->findById(1);
    }

    public function testUpdate()
    {
        $userId = 1;
        $userName = 'John Doe';
        $userEmail = 'john@gmail.com';
        $userBirth = '1990-01-01';
        $user = User::hydrateWithoutPasswordHash($userId, $userName, $userEmail, $userBirth);
        
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([
                'user_id' => $userId,
                'name' => $userName,
                'email' => $userEmail,
                'birth' => $userBirth
            ]);

        $mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->with("UPDATE users SET name = :name, email = :email, birth = :birth WHERE user_id = :user_id;")
            ->willReturn($mockStatement);
        
        
        $controller = $this->getMockBuilder(UserRepository::class)
                            ->setConstructorArgs([$mockPdo])
                            ->onlyMethods(['findById'])
                            ->getMock();
        
        $controller->expects($this->once())
                ->method('findById')
                ->with($userId)
                ->willReturn($user);
        
        
        $ReturnedUser = $controller->update($user);

        $this->assertEquals($user, $ReturnedUser);
    }

    public function testUpdatePdoException()
    {
        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage("Erro no banco");
        $userId = 1;
        $userName = 'John Doe';
        $userEmail = 'john@gmail.com';
        $userBirth = '1990-01-01';
        $user = User::hydrateWithoutPasswordHash($userId, $userName, $userEmail, $userBirth);
        
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockStatement->expects($this->never())
            ->method('execute')
            ->with([
                'user_id' => $userId,
                'name' => $userName,
                'email' => $userEmail,
                'birth' => $userBirth
            ]);

        $mockStatement->expects($this->never())
            ->method('rowCount')
            ->willReturn(1);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->expects($this->once())
            ->method('prepare')
            ->with("UPDATE users SET name = :name, email = :email, birth = :birth WHERE user_id = :user_id;")
            ->willThrowException(new \PDOException);
        
        
        $controller = $this->getMockBuilder(UserRepository::class)
                            ->setConstructorArgs([$mockPdo])
                            ->onlyMethods(['findById'])
                            ->getMock();
        
        $controller->expects($this->never())
                ->method('findById')
                ->with($userId)
                ->willReturn($user);
        
        
        $ReturnedUser = $controller->update($user);
    }
}