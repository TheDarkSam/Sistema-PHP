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

  $idCategoria = clear(trim($_POST['idCategoria']));
  $idArea = clear(trim($_POST['area']));
  $categoria = clear(trim($_POST['categoria']));

  $sqlRetencao = "UPDATE categoriaRetencao SET descCategoria = '{$categoria}', idArea = '{$idArea}' WHERE idCategoria='$idCategoria'";

  if (mysqli_query($connect, $sqlRetencao)) {
    $_SESSION['mensagem'] = ["texto" => "Categoria atualizada com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar categoria", "cor" => "alert-danger"];
  }


  mysqli_close($connect);
  header("Location: ../listCategoriaRetention");
}