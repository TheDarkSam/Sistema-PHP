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
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !isset($_POST['btnEditOrder'])) {
  header('Location: ../../../index');
} else {
  require_once '../../operations/Calculations.php';

  $calculations = new Calculations;

  $listaOrcamento = array();

  $idOrcamento = clear(trim($_POST['idOrcamento']));
  $valorHora = clear(trim($_POST['valorHora']));

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
  $valoresLGPD = $calculations->calcularLGPD($totalMinutos, $valorMinuto);

  mysqli_autocommit($connect, FALSE);
  $todasQuery = true;

  $data = date('Y/m/d');
  $hora = date('H:i:s');
  $sqlOrcamento = "UPDATE orcamento SET departamentos = '{$listaOrcamento['departamentos']}', compartilhamentos = '{$listaOrcamento['compartilhamentos']}', funcInternos = '{$listaOrcamento['funcInternos']}', funcExternos = '{$listaOrcamento['funcExternos']}', dispositivos = '{$listaOrcamento['dispositivos']}', sistemas = '{$listaOrcamento['sistemas']}', tipoDados = '{$listaOrcamento['tipoDados']}', ti = '{$listaOrcamento['ti']}', juridico = '{$listaOrcamento['juridico']}', politica = '{$listaOrcamento['politica']}', ferramentas = '{$listaOrcamento['ferramentas']}', observacoes = '{$listaOrcamento['observacoes']}' WHERE idOrcamento = '$idOrcamento'";
  $todasQuery = mysqli_query($connect, $sqlOrcamento) ? true : false;

  if ($todasQuery) {
    $sqlLGPD = "UPDATE lgpd SET coleta = '{$valoresLGPD['coleta']['minutos']}', gap = '{$valoresLGPD['gap']['minutos']}', planoAcao = '{$valoresLGPD['planoAcao']['minutos']}', implantacao = '{$valoresLGPD['implantacao']['minutos']}', relatorio = '{$valoresLGPD['relatorio']['minutos']}', treinamento = '{$valoresLGPD['treinamento']['minutos']}', dpo = '{$valoresLGPD['dpo']['minutos']}', validacao = '{$valoresLGPD['validacao']['minutos']}', melhorias = '{$valoresLGPD['melhorias']['minutos']}', total = '{$totalMinutos}' WHERE idOrcamento = '$idOrcamento'";
    $todasQuery = mysqli_query($connect, $sqlLGPD) ? true : false;

    $sqlPagamento = "UPDATE pagamento SET totalOrcamento = '0', valorEntrada = '0', totParcelas = '0' WHERE idPagamento = '{$idOrcamento}'";
    $todasQuery = mysqli_query($connect, $sqlPagamento) ? true : false;
  }

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Orcamento atualizado com sucesso", "cor" => "alert-success"];
  } else {
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar orcamento", "cor" => "alert-danger"];
  }
  
  mysqli_close($connect);
  header("Location: ../viewOrder?id={$idOrcamento}");
}