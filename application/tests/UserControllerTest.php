<?php

use PHPUnit\Framework\TestCase;
use APP\UserController;
use APP\UserService;
use APP\Exceptions\ServiceException;

class UserControllerTest extends TestCase
{

    public function testCreateUserWithValidParams()
    {
        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->method('register')->willReturn(1);
        $userController = new UserController($mockUserService);
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);

        $this->assertEquals($_SESSION['success_message'], 'Usuário cadastrado com sucesso!');
    }

    public function testCreateUserWithEmptyName()
    {
        $name = '';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Campo nome não pode ser vazio!');
    }

    public function testCreateUserWithEmptyEmail()
    {
        $name = 'John Doe';
        $email = '';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Email inválido');
    }

    public function testCreateUserWithInvalidEmail()
    {
        $name = 'John Doe';
        $email = 'john@gmail';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Email no formato inválido, verifique!');
    }

    public function testCreateUserWithEmptyBirth()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Data de nascimento deve ser preenchida com dados válidos');
    }

    public function testCreateUserWithInvalidBirth()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-40-10';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Data de nascimento deve ser preenchida com dados válidos');
    }

    public function testCreateUserWithInvalidPassword()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a';
        $passwordConfirmation = '123a';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Campo senha deve ter no mínimo 8 caracteres');
    }

    public function testCreateUserWithMissmatchPassword()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a4567';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willReturn(1);
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro: Senha e confirmação de senha estão diferentes. Verifique!');
    }

    public function testCreateUserWithExceptionFromService()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $passwordConfirmation = '123a456b';
        $mockUserSession = $this->createMock(UserService::class);
        $mockUserSession->method('register')->willThrowException(new ServiceException('Erro ao registrar usuário'));
        $userController = new UserController($mockUserSession);
        $userController->store($name, $email, $birth, $password, $passwordConfirmation);
        $this->assertEquals($_SESSION['error_message'], 'Erro ao registrar usuário');
    }
}