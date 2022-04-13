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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['excluir'] || !isset($_POST['btnDeleteOrder'])) {
  header('Location: ../../../index');
} else {
  $idOrcamento = clear($_POST['id']);

  mysqli_autocommit($connect, FALSE);

  $todasQuery = true;

  $todasQuery = mysqli_query($connect, "DELETE FROM pagamento WHERE idPagamento = '$idOrcamento'") ? true : false;
  $todasQuery = mysqli_query($connect, "DELETE FROM lgpd WHERE idOrcamento = '$idOrcamento'") ? true : false;
  $todasQuery = mysqli_query($connect, "DELETE FROM orcamento WHERE idOrcamento = '$idOrcamento'") ? true : false;
  
  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Orçamento excluido com sucesso!", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir {$todasQuery}", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../listOrder');
}
