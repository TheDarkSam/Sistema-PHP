<?php
// Sessao
session_start();

// Conexao
require_once '../../../conexao/usuarioConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$_SESSION['acesso']['usuario']['excluir'] || !isset($_POST['btnDeleteUser'])) {
  header('Location: ../../../index');
} else {
  $login = clear($_POST['login']);

  $sqlAcesso = "DELETE FROM controleAcesso WHERE idUsuario = (SELECT idUsuario FROM usuario WHERE login = '{$login}')";
  $todasQuery = mysqli_query($connect, $sqlAcesso) ? true : false;

  if ($todasQuery) {
    $sqlUsuario = "DELETE FROM usuario WHERE login='$login'";
    $todasQuery = mysqli_query($connect, $sqlUsuario) ? true : false;
  }

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "O login: {$login} foi excluido com sucesso!","cor" => "alert-success"];
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir o login: {$login}","cor" => "alert-danger"];
    mysqli_rollback($connect);    
  }

  mysqli_close($connect);
  header('Location: ../listUser');
}