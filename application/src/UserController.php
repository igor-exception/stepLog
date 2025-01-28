<?php
namespace APP;

use APP\UserService;
use APP\Helpers\DateHelper;
use APP\Exceptions\ServiceException;
use APP\Controller;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(): void
    {
        $this->render('user/create');
    }

    public function index(): void
    {
        $listUsers = [];
        try{
            $listUsers = $this->userService->getAllUsers();
        }catch(ServiceException $e) {
            $_SESSION['error_message'] = "Erro ao buscar usuários";
            session_write_close();
        }catch(\Throwable $e) {
            $_SESSION['error_message'] = "Erro ao processar solicitação";
            error_log($e->getMessage());
            session_write_close();
        }
        $this->render('user/list', ['listUsers' => $listUsers]);
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
            $this->redirect("/users");
        }catch(ServiceException $e) {
            $_SESSION['error_message'] = 'Erro ao registrar usuário';
            session_write_close();
            $this->redirect("/user/create");
        }catch(\Exception $e) {
            $_SESSION['error_message'] = 'Erro: '. $e->getMessage();
            session_write_close();
            $this->redirect("/user/create");
        }
    }

    public function show(string $id): void
    {
        try {
            $user = $this->userService->getUserById((int)$id);
            $this->render('user/show', ['user' => $user]);
        }catch(ServiceException $e) {
            $_SESSION['error_message'] = 'Erro ao buscar usuário';
            session_write_close();
            $this->redirect("/users");
        }catch(\Exception $e) {
            $_SESSION['error_message'] = 'Erro: '. $e->getMessage();
            session_write_close();
            $this->redirect("/users");
        }
    }

    public function edit(int $id): void
    {
        var_dump('Estou no EDIT do ID: ' . $id);
    }
}