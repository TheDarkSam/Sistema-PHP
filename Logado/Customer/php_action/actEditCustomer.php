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

function clearPhone($input)
{
  $remover = array("(", ")", "-", " ");
  $tel = str_replace($remover, "", $input);
  return $tel;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['editar'] || !isset($_POST['btnEditCustomer'])) {
  header('Location: ../../../index');
} else {
  $idCliente = clear(trim($_POST['idCliente']));
  $nomeEmpresa = clear(trim($_POST['nome_empresa']));
  $nomeResponsavel = clear(trim($_POST['nome_responsavel']));
  $email = clear(trim($_POST['email']));
  $telefoneFixo = clearPhone(clear(trim($_POST['telefone_fixo'])));
  $celular = clearPhone(clear(trim($_POST['celular'])));
  $whatsapp = clearPhone(clear(trim($_POST['whatsapp'])));

  $sql = "SELECT (SELECT t.telefone FROM contato t WHERE t.tipo = 'Whatsapp' AND t.idCliente = c.idCliente) AS whatsapp, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Celular' AND t.idCliente = c.idCliente) AS celular, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Fixo' AND t.idCliente = c.idCliente) AS fixo FROM cliente c WHERE c.idCliente = '{$idCliente}'";
  $resultado = mysqli_query($connect, $sql);
  $dados = mysqli_fetch_array($resultado);

  mysqli_autocommit($connect, FALSE);

  if (!empty($email)) {
    $sqlCliente = "UPDATE cliente SET nomeEmpresa = '$nomeEmpresa', nomeResponsavel = '$nomeResponsavel', email = '$email' WHERE idCliente='$idCliente'";
  } else {
    $sqlCliente = "UPDATE cliente SET nomeEmpresa = '$nomeEmpresa', nomeResponsavel = '$nomeResponsavel' WHERE idCliente='$idCliente'";
  }

  $todasQuery = true;

  $todasQuery = mysqli_query($connect, $sqlCliente) ? true : false;

  if ($todasQuery) {
    if ($dados['fixo'] != NULL) {
      if ($telefoneFixo == "") {
        $todasQuery = mysqli_query($connect, "DELETE FROM contato where idCliente = '$idCliente' and tipo = 'Fixo'");
      } else {
        $todasQuery = mysqli_query($connect, "UPDATE contato SET telefone = '$telefoneFixo' WHERE idCliente = '$idCliente' and tipo = 'Fixo'") ? true : false;
      }
    } else if ($telefoneFixo != "") {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ('$idCliente', '$telefoneFixo', 'Fixo')") ? true : false;
    }

    if ($dados['celular'] != NULL) {
      if ($celular == "") {
        $todasQuery = mysqli_query($connect, "DELETE FROM contato where idCliente = '$idCliente' and tipo = 'Celular'");
      } else {
        $todasQuery = mysqli_query($connect, "UPDATE contato SET telefone = '$celular' WHERE idCliente = '$idCliente' and tipo = 'Celular'") ? true : false;
      }
    } else if ($celular != "") {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ('$idCliente', '$celular', 'Celular')") ? true : false;
    }

    if ($dados['whatsapp'] != NULL) {
      if ($whatsapp == "") {
        $todasQuery = mysqli_query($connect, "DELETE FROM contato where idCliente = '$idCliente' and tipo = 'Whatsapp'");
      } else {
        $todasQuery = mysqli_query($connect, "UPDATE contato SET telefone = '$whatsapp' WHERE idCliente = '$idCliente' and tipo = 'Whatsapp'") ? true : false;
      }
    } else if ($whatsapp != "") {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ('$idCliente', '$whatsapp', 'Whatsapp')") ? true : false;
    }
  }

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Atualizado com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../editCustomer?id=' . $idCliente);
}
