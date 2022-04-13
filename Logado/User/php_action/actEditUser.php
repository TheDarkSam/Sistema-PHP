<?php

session_start();

require_once '../../../conexao/usuarioConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$_SESSION['acesso']['usuario']['editar'] || !isset($_POST['btnEditUser'])) {
  header('Location: ../../../index');
} else {
  $idUsuario = clear(trim($_POST['id']));
  $nome = clear(trim($_POST['nome']));
  $login = clear(trim($_POST['login']));
  $senha1 = clear(trim($_POST['senha1']));
  $senha2 = clear(trim($_POST['senha2']));
  $listaServicos = $_SESSION['listaServicos'];
  unset($_SESSION['listaServicos']);

  mysqli_autocommit($connect, FALSE);
  $todasQuery = false;

  $sqlEditar = "";

  if (isset($_POST['checkboxEditar']) && $senha1 == $senha2) {
    $novaSenha = md5($login . $senha1);
    $sqlEditar = "UPDATE usuario SET nome = '{$nome}', senha = '{$novaSenha}' WHERE idUsuario='{$idUsuario}'";
  } else {
    $sqlEditar = "UPDATE usuario SET nome = '{$nome}' WHERE idUsuario='{$idUsuario}'";
  }

  $todasQuery = mysqli_query($connect, $sqlEditar) ? true : false;

  foreach ($listaServicos as $linha => $valor) {
    if ($todasQuery) {
      $visualizar = isset($_POST["visualizar_{$linha}"]) ? true : 0;
      $incluir = isset($_POST["incluir_{$linha}"]) ? true : 0;
      $editar = isset($_POST["editar_{$linha}"]) ? true : 0;
      $excluir = isset($_POST["excluir_{$linha}"]) ? true : 0;

    $todasQuery = mysqli_query($connect, "UPDATE controleAcesso SET visualizar = $visualizar, incluir = $incluir, editar = $editar, excluir = $excluir WHERE idUsuario = '{$idUsuario}' AND pagina = '{$linha}'") ? true : false;
    }
  }

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Atualizado com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../editUser?id={$idUsuario}");
}
