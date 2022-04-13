<?php
class Formats
{
  public function formatarDinheiro($input)
  {
    return "R$ " . number_format($input, 2, ",", ".");
  }

  public function formatarHoraMinutos($input)
  {
    $horas = (int) ($input / 60);
    $minutos = (int) $input - ($horas * 60);
    return $horas . "h" . $minutos . "min";
  }

  public function minutosHora($input) {
    $hora = (int) ($input / 60);    
    $minuto = (int) $input - ($hora * 60);
    $obj = array('hora' => $hora, 'minuto' => $minuto);
    return $obj;
  }

  public function formatarOrcamento($input)
  {
    switch ($input) {
      case 'sim':
        return 'Sim';
        break;
      case 'nao':
        return 'Não';
        break;
      case 'sensiveis':
        return 'Sensíveis';
        break;
      case 'pessoais':
        return 'Pessoais';
        break;
      case 'alto':
        return 'Alto';
        break;
      case 'medio':
        return 'Médio';
        break;
      case 'baixo':
        return 'Baixo';
        break;
      default:
        return $input;
        break;
    }
  }  
}
