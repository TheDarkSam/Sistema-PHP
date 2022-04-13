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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['excluir'] || !isset($_POST['btnDeleteCategoria'])) {
  header('Location: ../../../index');
} else {

  $idCategoria = clear($_POST['idCategoria']);

  mysqli_autocommit($connect, FALSE);

  $todasQuery = true;

  $todasQuery = mysqli_query($connect, "DELETE FROM prazoRetencao WHERE idCategoria='$idCategoria'") ? true : false;
  $todasQuery = mysqli_query($connect, "DELETE FROM categoriaRetencao WHERE idCategoria='$idCategoria'") ? true : false;

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Categoria excluida com sucesso!", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir categoria", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../listCategoriaRetention');
}
