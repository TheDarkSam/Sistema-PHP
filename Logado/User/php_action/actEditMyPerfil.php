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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !isset($_POST['btnEditUser'])) {
  header('Location: ../../../index');
} else {
  $idUsuario = clear($_POST['id']);
  $nome = clear($_POST['nome']);
  $login = clear($_POST['login']);
  $senha1 = clear($_POST['senha1']);
  $senha2 = clear($_POST['senha2']);
  $listaServicos = null;
  if ($_SESSION['acesso']['usuario']['editar'] == 1 && isset($_SESSION['listaServicos'])) {
    $listaServicos = $_SESSION['listaServicos'];
  }
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

    // Se o usuario for administrador e tirar o seu proprio privilegios de editar usuarios
    if (!isset($_POST['editar_usuario'])) {
      $_SESSION['acesso']['usuario']['editar'] = 0;
    }
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar", "cor" => "alert-danger"];
  }

  // Atualizar sessão
  foreach ($listaServicos as $linha => $valor) {
    if (isset($_SESSION['acesso'][$linha]['visualizar'])) {
      $_SESSION['acesso'][$linha]['visualizar'] = isset($_POST["visualizar_{$linha}"]) ? true : 0;
    }

    if (isset($_SESSION['acesso'][$linha]['incluir'])) {
      $_SESSION['acesso'][$linha]['incluir'] = isset($_POST["incluir_{$linha}"]) ? true : 0;
    }

    if (isset($_SESSION['acesso'][$linha]['editar'])) {
      $_SESSION['acesso'][$linha]['editar'] = isset($_POST["editar_{$linha}"]) ? true : 0;
    }

    if (isset($_SESSION['acesso'][$linha]['excluir'])) {
      $_SESSION['acesso'][$linha]['excluir'] = isset($_POST["excluir_{$linha}"]) ? true : 0;
    }
  }

  mysqli_close($connect);
  header("Location: ../editMyPerfil");
}
