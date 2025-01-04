<?php require_once(__DIR__.'/../partial/header.php')
?>
<?php
# gera dados randoms pra criar usuario
$random = Random_int(1, 1000) . "_" . time() . "_" . Random_int(1, 1000);
$day = Random_int(1, 28);
$month = Random_int(1, 12);
$year = Random_int(1960, 2000);

$date = (new Datetime)->setDate($year, $month, $day);

$birth = $date->format('Y-m-d');
?>



        <form method="POST" action="/user/create">
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
<?php require_once(__DIR__.'/../partial/footer.php') ?>