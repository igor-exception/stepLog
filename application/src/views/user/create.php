<?php

    require_once '../../../vendor/autoload.php';
    session_start();

    use APP\UserController;
    use APP\UserRepository;
    use APP\UserService;


    if(isset($_POST) && !empty($_POST['name'])){
        $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
        $repository = new UserRepository($pdo);
        $service = new UserService($repository);
        $controller = new UserController($service);
        $controller->createUser(
            $_POST['name'],
            $_POST['email'],
            $_POST['birth'],
            $_POST['password'],
            $_POST['password-confirmation']
        );
    }


    // Verifica se existe uma mensagem de sucesso
    if (!empty($_SESSION['error_message'])) {
        echo "<div>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
        
        // Remove a mensagem para não exibir novamente
        unset($_SESSION['error_message']);
    }

# gera dados randoms pra criar usuario
$random = Random_int(1, 1000) . "_" . time() . "_" . Random_int(1, 1000);
$day = Random_int(1, 28);
$month = Random_int(1, 12);
$year = Random_int(1960, 2000);

$date = (new Datetime)->setDate($year, $month, $day);

$birth = $date->format('Y-m-d');
?>

<?php require_once('../partial/header.php') ?>

        <form method="POST" action="create.php">
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
<?php require_once('../partial/footer.php') ?>