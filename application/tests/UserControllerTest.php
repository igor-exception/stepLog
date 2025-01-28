<?php

use PHPUnit\Framework\TestCase;
use APP\UserController;
use APP\UserService;
use APP\Exceptions\ServiceException;
use APP\User;

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

    public function testIndex()
    {
        $mockService = $this->createMock(UserService::class);
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1960-01-01');
        $mockService->expects($this->once())
            ->method('getAllUsers')
            ->willReturn([
                $user
            ]);
        $userController = new UserController($mockService);
        ob_start();
        $userController->index();
        $output = ob_get_clean();
        $this->assertStringContainsString('John Doe', $output);
        $this->assertStringContainsString('john@gmail.com', $output);
        
    }

    public function testIndexServiceException()
    {
        ob_start();
        $mockService = $this->createMock(UserService::class);
        $mockService->expects($this->once())
            ->method('getAllUsers')
            ->willThrowException(new ServiceException);
        $userController = new UserController($mockService);
        $userController->index();
        ob_end_clean();
        $this->assertEquals('Erro ao buscar usuários', $_SESSION['error_message']);
    }

    public function testIndexThrowableException()
    {
        ob_start();
        $mockService = $this->createMock(UserService::class);
        $mockService->expects($this->once())
            ->method('getAllUsers')
            ->willThrowException(new Exception);
        $userController = new UserController($mockService);
        $userController->index();
        ob_end_clean();
        $this->assertEquals('Erro ao processar solicitação', $_SESSION['error_message']);
    }

    public function testShowSuccess()
    {
        $user = User::hydrateWithoutPasswordHash(1, 'John Doe', 'john@gmail.com', '1960-01-01');
        $mockUserService = $this->createMock(UserService::class);
        // Configura o mock do serviço
        $mockUserService->expects($this->once())
                        ->method('getUserById')
                        ->with(1)
                        ->willReturn($user);
    
        // Cria o controlador mockado e injeta o UserService
        $controller = $this->getMockBuilder(UserController::class)
                           ->setConstructorArgs([$mockUserService]) // Passa o serviço pelo construtor
                           ->onlyMethods(['render']) // Sobrescreve apenas o método render
                           ->getMock();
    
        // Configura a expectativa do render
        $controller->expects($this->once())
                   ->method('render')
                   ->with('user/show', ['user' => $user]);
    
        // Chama o método que está sendo testado
        $controller->show('1');
    }

    public function testShowServiceException()
    {
        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->expects($this->once())
            ->method('getUserById')
            ->with(100)
            ->willThrowException(new ServiceException('Usuário não encontrado'));
        
        $controller = $this->getMockBuilder(UserController::class)
                        ->setConstructorArgs([$mockUserService])
                        ->onlyMethods(['redirect'])
                        ->getMock();
        
        $controller->expects($this->once())
            ->method('redirect')
            ->with('/users');
        
        $controller->show(100);

        $this->assertNotEmpty($_SESSION['error_message']);
        $this->assertStringContainsString("Erro ao buscar usuário", $_SESSION['error_message']);
    }

    public function testActionShowThrowException()
    {
        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->expects($this->once())
            ->method('getUserById')
            ->with(100)
            ->willThrowException(new Exception('Falha ao buscar'));
        
        $controller = $this->getMockBuilder(UserController::class)
                        ->setConstructorArgs([$mockUserService])
                        ->onlyMethods(['redirect'])
                        ->getMock();
        $controller->expects($this->once())
            ->method('redirect')
            ->with('/users');

        $controller->show(100);
    }
}