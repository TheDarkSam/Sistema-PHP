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

$btn = false;

if (isset($_POST['btnAprovar']) xor isset($_POST['btnReprovar'])) {
  $btn = true;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !($btn && isset($_POST['id']))) {
  header('Location: ../../../index');
} else {
  setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
  $data = strftime('%Y/%m/%d', strtotime('today'));
  
  $idOrcamento = clear(trim($_POST['id']));

  if (isset($_POST['btnAprovar'])) {
    $situacao = "Aprovado";
  } else if (isset($_POST['btnReprovar'])){
    $situacao = "Reprovado";
  }

  $sql = "UPDATE orcamento SET situacao = '{$situacao}', dataValidado = '{$data}' WHERE idOrcamento = '{$idOrcamento}'";

  if (mysqli_query($connect, $sql)) {    
    $_SESSION['mensagem'] = ["texto" => "$situacao com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../viewOrder?id={$idOrcamento}");
}