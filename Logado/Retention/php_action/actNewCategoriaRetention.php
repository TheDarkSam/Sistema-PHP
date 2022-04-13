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

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['incluir'] || !isset($_POST['btnNewCategoria'])) {
  header('Location: ../../../index');
} else {

  $categoria = clear(trim($_POST['categoria']));
  $area = clear(trim($_POST['area']));

  $sqlCategoria = "INSERT INTO categoriaRetencao (descCategoria, idArea) VALUES ('{$categoria}', '{$area}')";

  if (mysqli_query($connect, $sqlCategoria)) {
    $_SESSION['mensagem'] = ["texto" => "Categoria gerada com sucesso", "cor" => "alert-success"];
  } else {
    $erro = mysqli_error($connect);

    if (strpos($erro, "Duplicate") !== false) {
      $erro = "Erro: Já existe essa categoria relacionada a área";
    } else {
      $erro = "Erro ao salvar categoria";
    }
    mysqli_rollback($connect);

    $_SESSION['mensagem'] = ["texto" => "$erro", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../newCategoriaRetention");
}
