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

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['documentacao']) || !$_SESSION['acesso']['documentacao']['incluir'] || !isset($_POST['btnNewArea'])) {
  header('Location: ../../../index');
} else {
  $descArea = clear(trim($_POST['descArea']));
  $sqlArea = "INSERT INTO areaDoc (descArea) VALUES ('{$descArea}')";

  if (mysqli_query($connect, $sqlArea)) {
    $_SESSION['mensagem'] = ["texto" => "Área gerada com sucesso", "cor" => "alert-success"];
  } else {
    $erro = mysqli_error($connect);

    if (strpos($erro, "Duplicate") !== false) {
      $erro = "Erro: Área ja existe";
    } else {
      $erro = "Erro ao salvar área";
    }

    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "$erro", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../newAreaDocumentation");
}
