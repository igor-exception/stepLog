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
}