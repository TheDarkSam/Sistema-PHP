<?php

class numeroPorExtenso
{
  public static function removerFormatacaoNumero($strNumero)
  {

    $strNumero = trim(str_replace("R$", null, $strNumero));

    $vetVirgula = explode(",", $strNumero);
    if (count($vetVirgula) == 1) {
      $acentos = array(".");
      $resultado = str_replace($acentos, "", $strNumero);
      return $resultado;
    } else if (count($vetVirgula) != 2) {
      return $strNumero;
    }

    $strNumero = $vetVirgula[0];
    $strDecimal = mb_substr($vetVirgula[1], 0, 2);

    $acentos = array(".");
    $resultado = str_replace($acentos, "", $strNumero);
    $resultado = $resultado . "." . $strDecimal;

    return $resultado;
  }

  public static function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
  {

    $valor = self::removerFormatacaoNumero($valor);

    $singular = null;
    $plural = null;

    if ($bolExibirMoeda) {
      $singular = array("Centavo", "Real", "Mil", "Milhão", "Bilhão", "Trilhão", "Quatrilhão");
      $plural = array("Centavos", "Reais", "Mil", "Milhões", "Bilhões", "Trilhões", "Quatrilhões");
    } else {
      $singular = array("", "", "Mil", "Milhão", "Bilhão", "Trilhão", "Quatrilhão");
      $plural = array("", "", "Mil", "Milhões", "Bilhões", "Trilhões", "Quatrilhões");
    }

    $c = array("", "Cem", "Duzentos", "Trezentos", "Quatrocentos", "Quinhentos", "Seiscentos", "Setecentos", "Oitocentos", "Novecentos");
    $d = array("", "Dez", "Vinte", "Trinta", "Quarenta", "Cinquenta", "Sessenta", "Setenta", "Oitenta", "Noventa");
    $d10 = array("Dez", "Onze", "Doze", "Treze", "Quatorze", "Quinze", "Dezesseis", "Dezessete", "Dezoito", "Dezenove");
    $u = array("", "Um", "Dois", "Três", "Quatro", "Cinco", "Seis", "Sete", "Oito", "Nove");


    if ($bolPalavraFeminina) {

      if ($valor == 1) {
        $u = array("", "Uma", "Duas", "Três", "Quatro", "Cinco", "Seis", "Sete", "Oito", "Nove");
      } else {
        $u = array("", "Um", "Duas", "Três", "Quatro", "Cinco", "Seis", "Sete", "Oito", "Nove");
      }


      $c = array("", "Cem", "Duzentas", "Trezentas", "Quatrocentas", "Quinhentas", "Seiscentas", "Setecentas", "Oitocentas", "Novecentas");
    }


    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);

    for ($i = 0; $i < count($inteiro); $i++) {
      for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
        $inteiro[$i] = "0" . $inteiro[$i];
      }
    }

    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
    $rt = null;
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
      $valor = $inteiro[$i];
      $rc = (($valor > 100) && ($valor < 200)) ? "Cento" : $c[$valor[0]];
      $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
      $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

      $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
      $t = count($inteiro) - 1 - $i;
      $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
      if ($valor == "000")
        $z++;
      elseif ($z > 0)
        $z--;

      if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
        $r .= (($z > 1) ? " de " : "") . $plural[$t];

      if ($r)
        $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    $rt = mb_substr($rt, 1);

    return ($rt ? trim($rt) : "Zero");
  }
}
