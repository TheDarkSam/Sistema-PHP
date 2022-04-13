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

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['documentacao']) || !$_SESSION['acesso']['documentacao']['incluir'] || !isset($_POST['btnNewDocumentation'])) {
  header('Location: ../../../index');
} else {
  $titulo = clear(trim($_POST['titulo']));
  $descricaoDoc = clear(trim($_POST['descricaoDoc']));
  $idArea = clear(trim($_POST['idArea']));

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
    $nomePasta = md5(date('d/m/Y H:i:s') . $tot . $titulo . $idArea);
    mysqli_autocommit($connect, FALSE);

    // Dados documentação
    $todasQuery = mysqli_query($connect, "INSERT INTO documentacao (titulo, descricaoDoc, idArea, nomePasta) VALUES ('{$titulo}', '{$descricaoDoc}','{$idArea}', '{$nomePasta}')");

    $ultimoId = mysqli_insert_id($connect);

    // Dados arquivos
    for ($i = 0; $i < $tot; $i++) {
      $todasQuery = mysqli_query($connect, "INSERT INTO arquivosDocumentacao (idDocumentacao, nomeArquivo) VALUES ($ultimoId, '{$arrayUnico['name'][$i]}')");
      if (!$todasQuery) {
        break;
      }
    }

    $pastaDestino = '../../../arquivos/documentation/' . $nomePasta . '/';

    if ($todasQuery && mkdir($pastaDestino, 0755) && mysqli_commit($connect)) {
      for ($i = 0; $i < $tot; $i++) {
        move_uploaded_file($arrayUnico['tmp_name'][$i], $pastaDestino . $arrayUnico['name'][$i]);
      }
      $_SESSION['mensagem'] = ["texto" => "Documentação gerada com sucesso", "cor" => "alert-success"];
    } else {
      mysqli_rollback($connect);
      $_SESSION['mensagem'] = ["texto" => "Erro ao gerar documentação", "cor" => "alert-danger"];
    }
  } else {
    $_SESSION['mensagem'] = ["texto" => "Arquivo(s) não suportado(s) ou inválido(s)", "cor" => "alert-danger"];
  }


  mysqli_close($connect);
  header("Location: ../newDocumentation");
}
