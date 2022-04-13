<?php
// Sessao
session_start();

// Conexao
require_once '../../../conexao/sistemaConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['documentacao']) || !$_SESSION['acesso']['documentacao']['excluir'] || !isset($_POST['btnDeleteArea'])) {
  header('Location: ../../../index');
} else {

  mysqli_autocommit($connect, FALSE);

  $todasQuery = true;

  $idArea = clear($_POST['id']);

  $todasQuery = mysqli_query($connect, "DELETE FROM documentacao WHERE idArea='$idArea'") ? true : false;
  $todasQuery = mysqli_query($connect, "DELETE FROM areaDoc WHERE idArea='$idArea'") ? true : false;

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Área excluida com sucesso!", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir área", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../listAreaDocumentation');
}

