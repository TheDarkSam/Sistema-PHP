<?php
// Sessao
session_start();
session_unset();
session_destroy();
session_start();

// Conexao
require_once '../conexao/usuarioConnect.php';

if (isset($_POST['btnLogin'])) {
  $login = mysqli_escape_string($connect, $_POST['login']);
  $senha = mysqli_escape_string($connect, $_POST['senha']);

  if (empty($login) or empty($senha)) {
    $_SESSION['mensagem'] = "O campo login/senha precisa ser preenchido";
    header('Location: ../login');
  } else {
    $sql = "SELECT login FROM usuario WHERE login = '$login'";
    $resultado = mysqli_query($connect, $sql);

    if (mysqli_num_rows($resultado) > 0) {
      $senha = md5($login . $senha);

      $sqlUsuario = "SELECT idUsuario, nome FROM usuario WHERE login = '$login' AND senha = '$senha'";
      $resultado = mysqli_query($connect, $sqlUsuario);

      if (mysqli_num_rows($resultado) == 1) {
        $dadosUsuario = mysqli_fetch_array($resultado);

        $sqlControleAcesso = "SELECT pagina, visualizar, incluir, editar, excluir FROM controleAcesso WHERE idUsuario = {$dadosUsuario['idUsuario']}";
        $resultadoAcesso = mysqli_query($connect, $sqlControleAcesso);

        if (mysqli_num_rows($resultadoAcesso) > 0) {
          $auxAcesso = array();

          while ($dadosAcesso = mysqli_fetch_array($resultadoAcesso)) {
            $auxAcesso += [$dadosAcesso['pagina'] => array('visualizar' => $dadosAcesso['visualizar'], 'incluir' => $dadosAcesso['incluir'], 'editar' => $dadosAcesso['editar'], 'excluir' => $dadosAcesso['excluir'])];
          }

          $_SESSION['acesso'] = $auxAcesso;
          $_SESSION['logado'] = true;
          $_SESSION['nome_usuario'] = strpos($dadosUsuario['nome'], " ") > 0 ? substr($dadosUsuario['nome'], 0, strpos($dadosUsuario['nome'], " ")) : $dadosUsuario['nome'];
          $_SESSION['id_usuario'] = $dadosUsuario['idUsuario'];

          mysqli_close($connect);
          
          header('Location: ../Logado/Home/index');
        } else {
          erroMsg("Erro tente novamente");
        }
      } else {
        erroMsg("Usuário e senha não conferem");
      }
    } else {
      erroMsg("Usuário inexistente");
    }
  }
} else {
  erroMsg("Erro tente novamente");
}


function erroMsg($msg)
{
  global $connect;
  $_SESSION['mensagem'] = $msg;
  mysqli_close($connect);
  header('Location: ../login');
}
