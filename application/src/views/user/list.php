<?php require_once(__DIR__.'/../partial/header.php');?>


<h1>Listar usuários</h1>

<table border="1" style="border-collapse: collapse; width: 50%;">
<thead>
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Nascimento</th>
    </tr>
</thead>
<tbody>
    <?php if(!empty($listUsers)) {?>
        <?php foreach($listUsers as $user) { ?>
            <tr>
                <td><?= $user['name']?></td>
                <td><?= $user['email']?></td>
                <td><?= $user['birth']?></td>
            </tr>
        <?php } ?>
    <?php }else{ ?>
        <td> - </td>
        <td> - </td>
        <td> - </td>
    <?php } ?>

</tbody>
</table>


<?php require_once(__DIR__.'/../partial/footer.php') ?>