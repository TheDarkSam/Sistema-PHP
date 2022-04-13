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

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !isset($_POST['btnEditLgpd'])) {
  header('Location: ../../../index');
} else {
  $idOrcamento = clear($_POST['idOrcamento']);

  $soma = 0;

  $aux = clear(trim($_POST['horaColeta'])) * 60 + clear(trim($_POST['minColeta']));
  $soma += $aux;
  $coleta = $aux;

  $aux = clear(trim($_POST['horaGap'])) * 60 + clear(trim($_POST['minGap']));
  $soma += $aux;
  $gap = $aux;

  $aux = clear(trim($_POST['horaPlanoAcao'])) * 60 + clear(trim($_POST['minPlanoAcao']));
  $soma += $aux;
  $planoAcao = $aux;

  $aux = clear(trim($_POST['horaImplantacao'])) * 60 + clear(trim($_POST['minImplantacao']));
  $soma += $aux;
  $implantacao = $aux;

  $aux = clear(trim($_POST['horaRelatorio'])) * 60 + clear(trim($_POST['minRelatorio']));
  $soma += $aux;
  $relatorio = $aux;

  $aux = clear(trim($_POST['horaTreinamento'])) * 60 + clear(trim($_POST['minTreinamento']));
  $soma += $aux;
  $treinamento = $aux;

  $aux = clear(trim($_POST['horaDpo'])) * 60 + clear(trim($_POST['minDpo']));
  $soma += $aux;
  $dpo = $aux;

  $aux = clear(trim($_POST['horaValidacao'])) * 60 + clear(trim($_POST['minValidacao']));
  $soma += $aux;
  $validacao = $aux;

  $aux = clear(trim($_POST['horaMelhorias'])) * 60 + clear(trim($_POST['minMelhorias']));
  $soma += $aux;
  $melhorias = $aux;
  
  $total = $soma;

  mysqli_autocommit($connect, FALSE);
  $todasQuery = true;

  $sqlLgpd = "UPDATE lgpd SET coleta = '{$coleta}', gap = '{$gap}', planoAcao = '$planoAcao', implantacao = '{$implantacao}', relatorio = '{$relatorio}', treinamento = '{$treinamento}', dpo = '{$dpo}', validacao = '{$validacao}', melhorias = '{$melhorias}', total = '{$total}' WHERE idOrcamento = '{$idOrcamento}'";
  $todasQuery = mysqli_query($connect, $sqlLgpd) ? true : false;

  $sqlPagamento = "UPDATE pagamento SET totalOrcamento = '0', valorEntrada = '0', totParcelas = '0' WHERE idPagamento = '{$idOrcamento}'";
  $todasQuery = mysqli_query($connect, $sqlPagamento) ? true : false;

  if ($todasQuery && mysqli_commit($connect)) {
    $_SESSION['mensagem'] = ["texto" => "Orçamento atualizado com sucesso", "cor" => "alert-success"];
  } else {    
    mysqli_rollback($connect);
    $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar orçamento", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header("Location: ../viewOrder?id={$idOrcamento}");
}