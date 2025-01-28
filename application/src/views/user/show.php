<?php require_once(__DIR__.'/../partial/header.php');?>

<div class="container">
    <h1>Informações do Usuário</h1>

    <div class="info">
        <label>Nome:</label>
        <span><?= $user->getName() ?></span>
    </div>

    <div class="info">
        <label>Email:</label>
        <span><?= $user->getEmail() ?></span>
    </div>

    <div class="info">
        <label>Data de Nascimento:</label>
        <span><?= $user->getBirth() ?></span>
    </div>
    
    <br/>
    <a href="/users/<?= $user->getId() ?>/edit">Editar</a>
</div>


<?php require_once(__DIR__.'/../partial/footer.php') ?>