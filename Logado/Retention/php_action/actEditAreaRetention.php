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

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['editar'] || !isset($_POST['btnEditArea'])) {
  header('Location: ../../../index');
} else {

  $idArea = clear(trim($_POST['idArea']));
  $descArea = clear(trim($_POST['descArea']));

  $sql = "UPDATE areaRetencao SET descArea = '{$descArea}' WHERE idArea='$idArea'";

  if (mysqli_query($connect, $sql)) {
    $_SESSION['mensagem'] = ["texto" => "Área atualizada com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar área", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../listAreaRetention");
}
