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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$_SESSION['acesso']['usuario']['incluir'] || !isset($_POST['btnNewUser'])) {
  header('Location: ../../../index');
} else {
  $nome = clear(trim($_POST['nome']));
  $login = clear(trim($_POST['login']));
  $senha1 = clear(trim($_POST['senha1']));
  $senha2 = clear(trim($_POST['senha2']));
  $listaServicos = $_SESSION['listaServicos'];
  unset($_SESSION['listaServicos']);

  if ($senha1 == $senha2) {
    $novaSenha = md5($login . $senha1);

    $sql = "SELECT * FROM usuario WHERE login = '{$login}'";
    $resultado = mysqli_query($connect, $sql);

    if (mysqli_num_rows($resultado) > 0) {
      $_SESSION['mensagem'] = ["texto" => "Erro: login já existe!", "cor" => "alert-danger"];
      mysqli_close($connect);
      header('Location: ../newUser');
    } else {
      mysqli_autocommit($connect, FALSE);
      $todasQuery = false;

      $sqlUsuario = "INSERT INTO usuario (nome, login, senha) VALUES ('$nome', '$login', '$novaSenha')";
      $todasQuery = mysqli_query($connect, $sqlUsuario) ? true : false;

      $ultimoId = 0;
      $ultimoId = mysqli_insert_id($connect);

      foreach ($listaServicos as $linha => $valor) {
        if ($todasQuery) {
          $visualizar = isset($_POST["visualizar_{$linha}"]) ? true : 0;
          $incluir = isset($_POST["incluir_{$linha}"]) ? true : 0;
          $editar = isset($_POST["editar_{$linha}"]) ? true : 0;
          $excluir = isset($_POST["excluir_{$linha}"]) ? true : 0;

          $todasQuery = mysqli_query($connect, "INSERT INTO controleAcesso (idUsuario, pagina, visualizar, incluir, editar, excluir) values ('{$ultimoId}', '${linha}', '{$visualizar}', '{$incluir}', '{$editar}', '{$excluir}')") ? true : false;
        }
      }

      if ($todasQuery && mysqli_commit($connect)) {
        $_SESSION['mensagem'] = ["texto" => "Cadastrado com sucesso", "cor" => "alert-success"];
      } else {
        mysqli_rollback($connect);
        $_SESSION['mensagem'] = ["texto" => "Erro ao cadastrar", "cor" => "alert-danger"];
      }
    }
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao cadastrar", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../newUser');
}
