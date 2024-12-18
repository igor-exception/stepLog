<?php
namespace APP;

use APP\UserService;
use APP\Helpers\DateHelper;

class UserController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(string $name, string $email, string $birth, string $password, string $passwordConfirmation): void
    {
        $errors = [];
        try {
            if(empty($name)) {
                throw new Exception('Campo nome não pode ser vazio!');
            }
            if(empty($email)) {
                throw new Exception('Email inválido');
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email no formato inválido, verifique!');
            }

            if(!DateHelper::isValidDate($birth)) {
                throw new Exception('Data de nascimento deve ser preenchida com dados válidos');
            }

            if(strlen($password) < 8) {
                throw new Exception('Campo senha deve ter no mínimo 8 caracteres');
            }

            if($password != $passwordConfirmation) {
                throw new Exception('Senha e confirmação de senha estão diferentes. Verifique!');
            }

            $userId = $this->userService->register($name, $email, $birth, $password);
            session_start();
            $_SESSION['success_message'] = "Usuário cadastrado com sucesso!";

            header("Location: /src/views/user/list.php");
            exit;
        }catch(ServiceException $e) {
            $_SESSION['error_message'] = 'Erro ao registrar usuário';
        }catch(\Exception $e) {
            $_SESSION['error_message'] = 'Erro fatal!';
        }
    }
}