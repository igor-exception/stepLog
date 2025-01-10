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
        $this->expectExceptionMessage("Erro ao consultar usuário:");

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
}