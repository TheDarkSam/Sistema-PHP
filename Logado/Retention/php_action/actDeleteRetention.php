<?php
// Sessao
session_start();

// Conexao
require_once '../../../conexao/sistemaConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['excluir'] || !isset($_POST['btnDeleteRetention'])) {
  header('Location: ../../../index');
} else {
  mysqli_autocommit($connect, FALSE);

  $idRetencao = clear($_POST['id']);

  $pasta = null;
  $todasQuery = true;

  $sqlNomePasta = "SELECT nomePasta FROM prazoRetencao WHERE idRetencao='$idRetencao'";
  $resultadoNomePasta = mysqli_query($connect, $sqlNomePasta);
  if (mysqli_num_rows($resultadoNomePasta) == 1) {
    $pasta = mysqli_fetch_array($resultadoNomePasta);
  }

  if ($pasta[0] != null) {
    $sqlArquivos = "SELECT nomeArquivo FROM arquivosRetencao WHERE idRetencao='$idRetencao'";
    $resultadoArquivos = mysqli_query($connect, $sqlArquivos);
    $arrayDeletar = array();

    if (mysqli_num_rows($resultadoArquivos) > 0) {
      while ($dadosPasta = mysqli_fetch_array($resultadoArquivos)) {
        array_push($arrayDeletar, $dadosPasta);
      }

      $todasQuery = mysqli_query($connect, "DELETE FROM arquivosRetencao WHERE idRetencao='$idRetencao'") ? true : false;
    }

    if ($todasQuery) {
      $todasQuery = mysqli_query($connect, "DELETE FROM prazoRetencao WHERE idRetencao='$idRetencao'") ? true : false;
    }

    if ($todasQuery) {
      $nomePasta = "../../../arquivos/retention/{$pasta[0]}";
      foreach ($arrayDeletar as $arquivo) {
        unlink("{$nomePasta}/{$arquivo['nomeArquivo']}");
      }
    }
  
    if ($todasQuery && rmdir($nomePasta) && mysqli_commit($connect)) {
      $_SESSION['mensagem'] = ["texto" => "Prazo de retenção excluido com sucesso!", "cor" => "alert-success"];
    } else {
      mysqli_rollback($connect);
      $_SESSION['mensagem'] = ["texto" => "Erro ao excluir", "cor" => "alert-danger"];
    }
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao excluir", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../listRetention');
}
