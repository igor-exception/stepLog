<?php
    require_once '../../../vendor/autoload.php';
    
    session_start();

    // Verifica se existe uma mensagem de sucesso
    if (!empty($_SESSION['success_message'])) {
        echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
        
        // Remove a mensagem para não exibir novamente
        unset($_SESSION['success_message']);
    }

    // Aqui vai o código para listar os usuários...
?>
<?php require_once('../partial/header.php') ?>

<h1>Listar usuários</h1>


<?php require_once('../partial/footer.php') ?>