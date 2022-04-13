<?php

// Utilizar a data para criar nome da pasta
date_default_timezone_set('America/Fortaleza');

session_start();

require_once '../../../conexao/sistemaConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['incluir'] || !isset($_POST['btnNewRetention'])) {
  header('Location: ../../../index');
} else {
  $idCategoria = clear(trim($_POST['idCategoria']));
  $titulo = clear(trim($_POST['titulo']));
  $descricao = clear(trim($_POST['descricao']));
  $destino = clear(trim($_POST['destino']));
  $finalidade = clear(trim($_POST['finalidade']));
  $prazo = clear(trim($_POST['prazo']));
  $link = clear(trim($_POST['link']));

  $existeAnexo = isset($_FILES['anexos']) ? true : false;
  $estadoAnexos = true;
  $tot = 0;

  if ($existeAnexo) {
    $arrayAnexo = $_FILES['anexos'];

    // Retirar somento com os nomes não duplicados
    $arrayNameUnico = array();
    $arrayNameUnico = array_unique($arrayAnexo['name']);

    // Array com arquivos não duplicados
    $arrayUnico = array();

    // Copiar para o arrayUnico os valores não repetidos    
    foreach ($arrayAnexo as $chave => $aux) {
      $arrayUnico[$chave] = array();

      foreach ($arrayNameUnico as $key => $aux2) {
        array_push($arrayUnico[$chave], $arrayAnexo[$chave][$key]);
      }
    }

    // Extensões permitidas
    $arrayExtensao = array("doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf");

    $tot = count($arrayUnico["name"]);


    if ($tot <= 10) {
      for ($i = 0; $i < $tot; $i++) {
        $extensao = pathinfo($arrayUnico['name'][$i], PATHINFO_EXTENSION);
        $extensao = strtolower($extensao);
        // Verificar tipo de arquivo e tamanho do arquivo
        if (!in_array($extensao, $arrayExtensao) || $arrayUnico['size'] <= 0) {
          $estadoAnexos = false;
          break;
        }
      }
    } else {
      $estadoAnexos = false;
    }
  }

  // Se não encontrar nenhum erro nos anexos
  if ($estadoAnexos) {
    $nomePasta = md5(date('d/m/Y H:i:s') . $idCategoria . $destino);
    mysqli_autocommit($connect, FALSE);

    // Dados prazo retenção
    $todasQuery = mysqli_query($connect, "INSERT INTO prazoRetencao (idCategoria, titulo, descricao, destino, finalidade, dataPrazo, link, nomePasta) VALUES ('{$idCategoria}', '{$titulo}','{$descricao}', '{$destino}','{$finalidade}','{$prazo}','{$link}','{$nomePasta}')");

    $ultimoId = mysqli_insert_id($connect);

    // Dados arquivos
    for ($i = 0; $i < $tot; $i++) {
      $todasQuery = mysqli_query($connect, "INSERT INTO arquivosRetencao (idRetencao, nomeArquivo) VALUES ($ultimoId, '{$arrayUnico['name'][$i]}')");
      if (!$todasQuery) {
        break;
      }
    }

    $pastaDestino = '../../../arquivos/retention/' . $nomePasta . '/';

    if ($todasQuery && mkdir($pastaDestino, 0755) && mysqli_commit($connect)) {
      for ($i = 0; $i < $tot; $i++) {
        move_uploaded_file($arrayUnico['tmp_name'][$i], $pastaDestino . $arrayUnico['name'][$i]);
      }
      $_SESSION['mensagem'] = ["texto" => "Prazo de retenção gerado com sucesso", "cor" => "alert-success"];
    } else {
      mysqli_rollback($connect);
      $_SESSION['mensagem'] = ["texto" => "Erro ao gerar prazo de retenção", "cor" => "alert-danger"];
    }
  } else {
    $_SESSION['mensagem'] = ["texto" => "Arquivo(s) não suportado(s) ou inválido(s)", "cor" => "alert-danger"];
  }


  mysqli_close($connect);
  header("Location: ../newRetention");
}
