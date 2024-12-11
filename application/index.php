<?php

require_once './vendor/autoload.php';

use APP\UserService;
use APP\UserRepository;

echo date('d/m/Y H:i:s');
echo "<h1>Hello steplog</h1>";

    if(isset($_POST) && !empty($_POST['name'])){
        $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $repository = new UserRepository($pdo);
        $userService = new UserService($repository);
        $returnAddedUser = $userService->registerUser($_POST['name'], $_POST['email'], $_POST['birth'], $_POST['password'], $_POST['password-confirmation']);
        if($returnAddedUser) {
            echo "<h1>Usuario Adicionado com sucesso</h1>";
        }else{
            echo "<h1>Erro ao adicionar usuário</h1>";
        }
    }

# gera dados randoms pra criar usuario
$random = Random_int(1, 1000) . "_" . time() . "_" . Random_int(1, 1000);
$day = Random_int(1, 28);
$month = Random_int(1, 12);
$year = Random_int(1960, 2000);

$date = (new Datetime)->setDate($year, $month, $day);

$birth = $date->format('Y-m-d');
?>

<html>
    <head>
    </head>
    <body>
        <form method="POST" action="/">
            <label for="name">Nome: </label>
            <input type="text" name="name" value="<?= 'John ' . $random?>"><br><br>
            
            <label for="email">Email: </label>
            <input type="email" name="email" value="<?= 'john_' . $random . '@gmail.com'?>"><br><br>

            <label for="birth">Nascimento: </label>
            <input type="date" name="birth" value="<?= $birth?>"><br><br>

            <label for="password">Senha: </label>
            <input type="password" name="password" value="123a456b"><br><br>

            <label for="password-confirmation">Confirmação de senha: </label>
            <input type="password" name="password-confirmation" value="123a456b"><br><br>

            <input type="submit" value="Adicionar usuário">
        </form>
    </body>
</html>