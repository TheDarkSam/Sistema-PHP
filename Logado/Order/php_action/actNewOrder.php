<?php

session_start();

require_once '../../../conexao/sistemaConnect.php';
date_default_timezone_set('America/Sao_Paulo');

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['incluir'] || !isset($_POST['btnNewOrder'])) {
  header('Location: ../../../index');
} else {
  require_once '../../operations/Calculations.php';

  $valorHora = str_replace(".", "", clear($_POST['valorHora']));
  $valorHora = str_replace(",", ".", $valorHora);

  $todasQuery = false;
  if ($valorHora >= 10) {
    $calculations = new Calculations;

    $listaOrcamento = array();

    $idCliente = clear(trim($_POST['cliente']));
    $idUsuario = clear($_SESSION['id_usuario']);

    $listaOrcamento['departamentos'] = clear(trim($_POST['departamentos']));
    $listaOrcamento['funcInternos'] = clear(trim($_POST['funcInternos']));
    $listaOrcamento['funcExternos'] = clear(trim($_POST['funcExternos']));
    $listaOrcamento['dispositivos'] = clear(trim($_POST['dispositivos']));
    $listaOrcamento['sistemas'] = clear(trim($_POST['sistemas']));
    $listaOrcamento['tipoDados'] = clear(trim($_POST['tipoDados']));
    $listaOrcamento['ti'] = clear(trim($_POST['ti']));
    $listaOrcamento['juridico'] = clear(trim($_POST['juridico']));
    $listaOrcamento['politica'] = clear(trim($_POST['politica']));
    $listaOrcamento['compartilhamentos'] = clear(trim($_POST['compartilhamentos']));
    $listaOrcamento['ferramentas'] = clear(trim($_POST['ferramentas']));
    $listaOrcamento['observacoes'] = clear(trim($_POST['observacoes']));
    $listaOrcamento['valorHora'] = $valorHora;

    $valorMinuto = round($valorHora / 60, 2);

    $valoresOrcamento = $calculations->calcularListaOrcamento($listaOrcamento, $valorMinuto);
    $totalMinutos = $valoresOrcamento["total"]["minutos"];
    $valoresLGPD = $calculations->calcularLGPD($totalMinutos, $valorMinuto, $valorHora);

    mysqli_autocommit($connect, FALSE);
    $todasQuery = true;

    $data = date('Y/m/d');
    $hora = date('H:i:s');
    $sqlOrcamento = "INSERT INTO orcamento (idCliente, departamentos, compartilhamentos, funcInternos, funcExternos, dispositivos, sistemas, tipoDados, ti, juridico, politica, ferramentas, observacoes, valorHora, situacao, dataCriado) VALUES ('$idCliente', '{$listaOrcamento['departamentos']}', '{$listaOrcamento['compartilhamentos']}', '{$listaOrcamento['funcInternos']}', '{$listaOrcamento['funcExternos']}', '{$listaOrcamento['dispositivos']}', '{$listaOrcamento['sistemas']}', '{$listaOrcamento['tipoDados']}', '{$listaOrcamento['ti']}', '{$listaOrcamento['juridico']}', '{$listaOrcamento['politica']}', '{$listaOrcamento['ferramentas']}', '{$listaOrcamento['observacoes']}', '{$listaOrcamento['valorHora']}', 'Pendente', '{$data}')";

    $todasQuery = mysqli_query($connect, $sqlOrcamento) ? true : false;

    $ultimoId = 0;

    if ($todasQuery) {
      $ultimoId = mysqli_insert_id($connect);
      $sqlLGPD = "INSERT INTO lgpd (idOrcamento, coleta, gap, planoAcao, implantacao, relatorio, treinamento, dpo, validacao, melhorias, total) VALUES ('$ultimoId', '{$valoresLGPD['coleta']['minutos']}', '{$valoresLGPD['gap']['minutos']}','{$valoresLGPD['planoAcao']['minutos']}', '{$valoresLGPD['implantacao']['minutos']}', '{$valoresLGPD['relatorio']['minutos']}', '{$valoresLGPD['treinamento']['minutos']}', '{$valoresLGPD['dpo']['minutos']}', '{$valoresLGPD['validacao']['minutos']}', '{$valoresLGPD['melhorias']['minutos']}', '{$totalMinutos}')";

      $todasQuery = mysqli_query($connect, $sqlLGPD) ? true : false;

      $sqlPagamento = "INSERT INTO pagamento (idPagamento, idOrcamento, totalOrcamento, valorEntrada, totParcelas) VALUES ('{$ultimoId}', '{$ultimoId}', '0', '0', '0')";
      $todasQuery = mysqli_query($connect, $sqlPagamento) ? true : false;
    }
  }

  if ($todasQuery && mysqli_commit($connect)) {
    mysqli_close($connect);
    $_SESSION['mensagem'] = ["texto" => "Orcamento gerado com sucesso", "cor" => "alert-success"];
    header("Location: ../viewOrder?id={$ultimoId}");
  } else {
    $_SESSION['mensagem'] = ["texto" => "Erro ao gerar orcamento", "cor" => "alert-danger"];
    mysqli_rollback($connect);
    mysqli_close($connect);
    header("Location: ../newOrder");
  }
}
