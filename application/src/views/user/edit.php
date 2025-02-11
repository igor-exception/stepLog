<?php require_once(__DIR__.'/../partial/header.php');?>


        <form method="POST" action="/users/<?= $user->getId()?>">
            <label for="name">Nome: </label>
            <input type="text" name="name" value="<?= $user->getName()?>"><br><br>
            
            <label for="email">Email: </label>
            <input type="email" name="email" value="<?= $user->getEmail()?>"><br><br>

            <label for="birth">Nascimento: </label>
            <input type="date" name="birth" value="<?= $user->getBirth()?>"><br><br>
            
            <input type="hidden" name="id" value="<?= $user->getId()?>">
            <input type="hidden" name="_method" value="PUT">

            <input type="submit" value="Atualizar usuário">
        </form>
<?php require_once(__DIR__.'/../partial/footer.php') ?>