<?php

class Calculations
{
  public function calcularListaOrcamento($listaOrcamento, $valorMinuto)
  {
    $tempoDepartamentos = 10;
    $tempoFuncInternos = 10;
    $tempoFuncExternos = 10;
    $tempoDispositivos = 10;
    $tempoSistemas = 180; // 3 horas
    $tempoCompartilhamento = 5;

    //$valorMinuto = $listaOrcamento['valorMinuto'];
    $precoTotal = 0;
    $totalMinutos = 0;

    $valoresOrcamento = array();

    $minutos = $listaOrcamento['departamentos'] * $tempoDepartamentos;
    $valoresOrcamento["departamentos"] = array("minutos" => $minutos);

    $minutos = $listaOrcamento['funcInternos'] * $tempoFuncInternos;
    $valoresOrcamento["funcInternos"] = array("minutos" => $minutos);

    $minutos = $listaOrcamento['funcExternos'] * $tempoFuncExternos;
    $valoresOrcamento["funcExternos"] = array("minutos" => $minutos);

    $minutos = $listaOrcamento['dispositivos'] * $tempoDispositivos;
    $valoresOrcamento["dispositivos"] = array("minutos" => $minutos);

    $minutos = $listaOrcamento['sistemas'] * $tempoSistemas;
    $valoresOrcamento["sistemas"] = array("minutos" => $minutos);

    $minutos = $listaOrcamento['compartilhamentos'] * $tempoCompartilhamento;
    $valoresOrcamento["compartilhamentos"] = array("minutos" => $minutos);

    $minutosAux = 0;
    foreach ($valoresOrcamento as $item) {
      $minutosAux += (int) $item["minutos"];
    }

    $totalMinutos = $minutosAux;

    if ($listaOrcamento['tipoDados'] == "pessoais") {
      $totalMinutos += $minutosAux * 0;
    } else if ($listaOrcamento['tipoDados'] == "sensiveis") {
      $totalMinutos += $minutosAux * 0.05;
    }

    if ($listaOrcamento['politica'] == "sim") {
      $totalMinutos += $minutosAux * 0.025;
    } else if ($listaOrcamento['politica'] == "nao") {
      $totalMinutos += $minutosAux * 0.05;
    }

    if ($listaOrcamento['ferramentas'] == "alto") {
      $totalMinutos += $minutosAux * 0.025;
    } else if ($listaOrcamento['ferramentas'] == "medio") {
      $totalMinutos += $minutosAux * 0.05;
    } else if ($listaOrcamento['ferramentas'] == "baixo") {
      $totalMinutos += $minutosAux * 0.075;
    }

    if ($listaOrcamento['observacoes'] == "sim") {
      $totalMinutos += $minutosAux * 0.025;
    } else if ($listaOrcamento['observacoes'] == "nao") {
      $totalMinutos += $minutosAux * 0;
    }

    if ($listaOrcamento['ti'] == "sim") {
      $totalMinutos += $minutosAux * 0;
    } else if ($listaOrcamento['ti'] == "nao") {
      $totalMinutos += $minutosAux * 0.5;
    }

    if ($listaOrcamento['juridico'] == "sim") {
      $totalMinutos += $minutosAux * 0;
    } else if ($listaOrcamento['juridico'] == "nao") {
      $totalMinutos += $minutosAux * 0.5;
    }

    $totalMinutos = round($totalMinutos);
    $precoTotal = round($totalMinutos * $valorMinuto, 2);

    $valoresOrcamento["total"] = array("minutos" => $totalMinutos, "preco" => $precoTotal);

    return $valoresOrcamento;
  }

  public function calcularLGPD($totalMinutos, $valorMinuto)
  {
    $listaLGPD = array();

    $minutosAux = round($totalMinutos * 0.15);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["coleta"] = array("texto" => "Coleta", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.08);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["gap"] = array("texto" => "Gap Analysis", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.07);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["planoAcao"] = array("texto" => "Plano de Ação", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.35);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["implantacao"] = array("texto" => "Implantação", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.09);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["relatorio"] = array("texto" => "Relatório de Impacto", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.15);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["treinamento"] = array("texto" => "Treinamento", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.03);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["dpo"] = array("texto" => "DPO", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.04);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["validacao"] = array("texto" => "Validação", "minutos" => $minutosAux, "preco" => $precoAux);

    $minutosAux = round($totalMinutos * 0.04);
    $precoAux = $minutosAux * $valorMinuto;
    $listaLGPD["melhorias"] = array("texto" => "Melhorias", "minutos" => $minutosAux, "preco" => $precoAux);    

    $totMinutosAux = 0;
    foreach ($listaLGPD as $item) {
      $totMinutosAux += $item["minutos"];
    }

    if ($totMinutosAux != $totalMinutos) {
      $aux = $totalMinutos - $totMinutosAux;
      $minutosAux = $listaLGPD["implantacao"]["minutos"] + $aux;
      $precoAux = $minutosAux * $valorMinuto;
      $listaLGPD["implantacao"] = array("texto" => "Implantação", "minutos" => $minutosAux, "preco" => $precoAux);
    }

    $precoAux = $totalMinutos * $valorMinuto;
    $listaLGPD["total"] = array("texto" => "Total", "minutos" => $totalMinutos, "preco" => $precoAux);

    return $listaLGPD;
  }
}
