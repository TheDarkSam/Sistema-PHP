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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['excluir'] || !isset($_POST['btnDeleteCustomer'])) {
  header('Location: ../../../index');
} else {
  $idCliente = clear($_POST['idCliente']);

  mysqli_autocommit($connect, FALSE); 

  $todasQuery = true;

  $todasQuery = mysqli_query($connect, "DELETE FROM contato WHERE idCliente='$idCliente'") ? true : false;
  $todasQuery = mysqli_query($connect, "DELETE FROM cliente WHERE idCliente='$idCliente'") ? true : false;  

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Cliente excluído com sucesso!","cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir","cor" => "alert-danger"];
  }
}

mysqli_close($connect);
header('Location: ../listCustomer');