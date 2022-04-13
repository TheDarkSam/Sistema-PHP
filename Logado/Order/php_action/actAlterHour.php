<?php

session_start();

require_once '../../../conexao/sistemaConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !isset($_POST['btnAlterHour'])) {
  header('Location: ../../../index');
} else {

  $idOrcamento = clear(trim($_POST['id']));
  $novoValorHora = clear(trim($_POST['valorHora']));
  $novoValorHora = str_replace('.', '', $novoValorHora);
  $novoValorHora = str_replace(',', '.', $novoValorHora);

  if ($novoValorHora >= 10) {
    $sql = "UPDATE orcamento SET valorHora = {$novoValorHora} WHERE idOrcamento = {$idOrcamento}";
  } else {
    $sql = "";
  }  

  if (mysqli_query($connect, $sql)) {
    $_SESSION['mensagem'] = ["texto" => "Valor da hora atualizado com sucesso", "cor" => "alert-success"];
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar valor da hora", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../viewOrder?id={$idOrcamento}");
}
