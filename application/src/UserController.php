<?php
namespace APP;

use APP\UserService;
use APP\Helpers\DateHelper;
use APP\Exceptions\ServiceException;

class UserController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(): void
    {
        require __DIR__ . '/views/user/create.php';
    }

    public function index(): void
    {
        require __DIR__ . '/views/user/list.php';
    }

    public function store(string $name, string $email, string $birth, string $password, string $passwordConfirmation): void
    {
        $errors = [];
        try {
            if(empty($name)) {
                throw new \Exception('Campo nome não pode ser vazio!');
            }
            if(empty($email)) {
                throw new \Exception('Email inválido');
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Email no formato inválido, verifique!');
            }

            if(!DateHelper::isValidDate($birth)) {
                throw new \Exception('Data de nascimento deve ser preenchida com dados válidos');
            }

            if(strlen($password) < 8) {
                throw new \Exception('Campo senha deve ter no mínimo 8 caracteres');
            }

            if($password != $passwordConfirmation) {
                throw new \Exception('Senha e confirmação de senha estão diferentes. Verifique!');
            }
            $userId = $this->userService->register($name, $email, $birth, $password);
            $_SESSION['success_message'] = "Usuário cadastrado com sucesso!";
            session_write_close();
            header("Location: /users");
            return;
        }catch(ServiceException $e) {
            $_SESSION['error_message'] = 'Erro ao registrar usuário';
            session_write_close();
            header("Location: /user/create");
            return;
        }catch(\Exception $e) {
            $_SESSION['error_message'] = 'Erro: '. $e->getMessage();
            session_write_close();
            header("Location: /user/create");
            return;
        }
    }

}