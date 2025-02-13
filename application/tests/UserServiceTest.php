<?php

use PHPUnit\Framework\TestCase;
use APP\User;
use APP\UserRepository;
use APP\UserService;
use APP\Exceptions\RepositoryException;
use APP\Exceptions\ServiceException;


class UserServiceTest extends TestCase
{
    public function testUserServiceWithValidData()
    {
        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository->method('save')->willReturn('1');
        $userService = new UserService($mockUserRepository);
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        
        $userId = $userService->register($name, $email, $birth, $password);
        $this->assertEquals(1, $userId);
    }

    public function testUserServiceWithRepositoryException()
    {
        $this->ExpectException(ServiceException::class);
        $this->ExpectExceptionMessage('Erro ao registrar usuário: Erro no banco.');
        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository->method('save')->willThrowException(new RepositoryException('Erro no banco.'));
        $userService = new UserService($mockUserRepository);
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        
        $userId = $userService->register($name, $email, $birth, $password);
    }

    public function testGetAllUsers()
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
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($listUsers);
        
        $userService = new UserService($mockRepository);
        $usersListData = $userService->getAllUsers();
        $this->assertEquals($listUsers, $usersListData);
    }

    public function testGetAllUsersException()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Erro ao consultar usuário");

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
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('findAll')
            ->willThrowException(new RepositoryException());
        $userService = new UserService($mockRepository);
        $usersListData = $userService->getAllUsers();
    }

    public function testGetUserById()
    {
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1960-01-01');
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('findById')
            ->willReturn($user);
        
        $userService = new UserService($mockRepository);
        $usersData = $userService->getUserById(1);
        $this->assertEquals($user, $usersData);
    }

    public function testGetUserByIdException()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Usuário não encontrado");

        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1960-01-01');
        
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);
        $userService = new UserService($mockRepository);
        $usersListData = $userService->getUserById(1);
    }

    public function testGetUserByIdRepositoryException()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Erro ao consultar usuário pelo ID:");

        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1960-01-01');
        
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willThrowException(new RepositoryException("Erro no banco"));
        $userService = new UserService($mockRepository);
        $usersListData = $userService->getUserById(1);
    }

    public function testUpdate()
    {
        $updatedUser = User::hydrateWithoutPasswordHash(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('update')
            ->with($updatedUser)
            ->willReturn($updatedUser);
        
        $userService = new UserService($mockRepository);
        $usersData = $userService->update(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
        $this->assertEquals($updatedUser, $usersData);
    }

    public function testUpdateUserException()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Erro ao atualizar usuário");

        $user = User::hydrateWithoutPasswordHash(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
        
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('update')
            ->with($user)
            ->willReturn(null);
        $userService = new UserService($mockRepository);
        $usersListData = $userService->update(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
    }

    public function testUpdateRepositoryException()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Erro ao atualizar usuário:");

        $user = User::hydrateWithoutPasswordHash(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
        
        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->once())
            ->method('update')
            ->with($user)
            ->willThrowException(new RepositoryException("Erro no banco."));
        $userService = new UserService($mockRepository);
        $usersListData = $userService->update(1, 'John Doe da silva', 'john@gmail.com', '1960-01-01');
    }
}