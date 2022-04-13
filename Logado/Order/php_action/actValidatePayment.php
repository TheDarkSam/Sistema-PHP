<?php
// Sessão
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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !isset($_POST['btnValidate'])) {
  header('Location: ../../../index');
} else {
  $todasQuery = false;

  $idOrcamento = clear($_POST['id']);

  if (isset($_POST['radioTipoEntrada'])) {
    $radioTipoEntrada = clear($_POST['radioTipoEntrada']);
    $totalOrcamento = clear(trim($_POST['totalOrcamento']));
    $remover = array("R$", ".", " ");
    $totalOrcamento = str_replace($remover, "", $totalOrcamento);
    $totalOrcamento = str_replace(",", ".", $totalOrcamento);
    $totalOrcamento = round($totalOrcamento, 2);
    $valorEntrada = 0;
    $totParcelas = 0;

    if ($radioTipoEntrada == "percentual" || $radioTipoEntrada == "dinheiro") {
      $valorEntrada = round(clear($_POST['valorEntrada']), 2);
      $totParcelas = clear($_POST['totParcelas']);
    } elseif ($radioTipoEntrada == "avista") {
      $valorEntrada = round($totalOrcamento, 2);
    } elseif ($radioTipoEntrada == "semEntrada") {
      $totParcelas = clear($_POST['totParcelas']);
    }

    mysqli_autocommit($connect, FALSE);
    $todasQuery = true;

    $sqlPagamento = "UPDATE pagamento SET totalOrcamento = '{$totalOrcamento}', valorEntrada = '{$valorEntrada}', totParcelas = '${totParcelas}' WHERE idPagamento = '{$idOrcamento}'";
    $todasQuery = mysqli_query($connect, $sqlPagamento) ? true : false;
  }

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Pagamento salvo com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao salvar pagamento", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../viewOrder?id={$idOrcamento}");
}
