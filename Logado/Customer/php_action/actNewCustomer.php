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
  $input = clear($input);
  $remover = array("(", ")", "-", " ");
  $tel = str_replace($remover, "", $input);
  return $tel;
}

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['incluir'] || !isset($_POST['btnNewCustomer'])) {
  mysqli_close($connect);
  header('Location: ../../../index');
} else {
  $nomeEmpresa = clear(trim($_POST['nome_empresa']));
  $nomeResponsavel = clear(trim($_POST['nome_responsavel']));
  $email = clear(trim($_POST['email']));
  $telefoneFixo = clearPhone(trim($_POST['telefone_fixo']));
  $celular = clearPhone(trim($_POST['celular']));
  $whatsapp = clearPhone(trim($_POST['whatsapp']));

  if (!empty($email)) {
    $sqlCliente = "INSERT INTO cliente (nomeEmpresa, nomeResponsavel, email) VALUES ('{$nomeEmpresa}', '{$nomeResponsavel}', '{$email}')";
  } else {
    $sqlCliente = "INSERT INTO cliente (nomeEmpresa, nomeResponsavel) VALUES ('$nomeEmpresa', '$nomeResponsavel')";
  }

  mysqli_autocommit($connect, FALSE);

  $todasQuery = mysqli_query($connect, $sqlCliente) ? true : false;

  if ($todasQuery) {
    $ultimoId = mysqli_insert_id($connect);

    if (!empty($telefoneFixo)) {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ($ultimoId, '$telefoneFixo', 'Fixo')") ? true : false;
    }

    if (!empty($celular)) {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ($ultimoId, '$celular', 'Celular')") ? true : false;
    }

    if (!empty($whatsapp)) {
      $todasQuery = mysqli_query($connect, "INSERT INTO contato (idCliente, telefone, tipo) VALUES ($ultimoId, '$whatsapp', 'Whatsapp')") ? true : false;
    }
  }

  if ($todasQuery && mysqli_commit($connect)) {    
    $_SESSION['mensagem'] = ["texto" => "Cadastrado com sucesso", "cor" => "alert-success"];    
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao cadastrar", "cor" => "alert-danger"];
    mysqli_rollback($connect);
  }

  mysqli_close($connect);
  header('Location: ../newCustomer');
}