<?php
/* 
  Toda requisicao deve passar pelo index.php, que gerencia as rotas.
  Foi criada uma constante global APP_START, que diz que passou de fato pela index.php
  Aqui no header eh feita a verificacao se realmente passou pelo index.php
  O objetivo eh evitar por exemplo isso: header("Location:/src/views/user/list.php"); onde
  no controller poderia chamar diretamente uma pagina. Se isso ocorrer, quebra totalmente o fluxo de sessions.
  Forma errada de redirecionar: header("Location:/src/views/user/list.php");
  Forma correta de redirecionar: header("Location: /users");

  usando a forma correta, passara pelo array de endpoint para resolver as rotas.
*/

if (!defined('APP_START')) {
    http_response_code(403);
    die('Acesso direto não permitido. Todo redirecionamento de rota deve passar pelo ./index.php');
}

function displayFlashMessages() {
  if(!empty($_SESSION['success_message'])) {
    echo '<div>' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
  }elseif(!empty($_SESSION['error_message'])) {
    echo '<div>' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
  }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    
  </head>
  <body>
  <br><a href="/">Voltar</a><br>
<?php displayFlashMessages(); ?>
