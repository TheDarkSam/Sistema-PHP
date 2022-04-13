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

$idDocumentacao = $_POST['idDocumentacao'];
$pastaDocumentacao = null;

$sqlDocumentacao = "SELECT d.nomePasta FROM documentacao d WHERE idDocumentacao = '{$idDocumentacao}'";
$resultadoDocumentacao = mysqli_query($connect, $sqlDocumentacao);
if (mysqli_num_rows($resultadoDocumentacao) == 1) {
  $pastaDocumentacao = mysqli_fetch_array($resultadoDocumentacao);
}

if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['documentacao']) || !$_SESSION['acesso']['documentacao']['editar'] || !isset($_POST['btnEditDocumentation'])) {
  header('Location: ../../../index');
} else {
  if ($pastaDocumentacao != null) {
    $pastaDestino = '../../../arquivos/documentation/' . $pastaDocumentacao['nomePasta'] . '/';

    $titulo = clear(trim($_POST['titulo']));
    $descricaoDoc = clear(trim($_POST['descricaoDoc']));
    $idArea = clear(trim($_POST['idArea']));

    $todasQuery = true;

    $existeAnexo = isset($_FILES['anexos']) ? true : false;
    $estadoAnexos = true;
    $tot = 0;

    // Verificações dos novos anexos
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
    // ./ Verificações dos novos anexos

    // Se não encontrar nenhum erro nos anexos
    if ($estadoAnexos) {
      mysqli_autocommit($connect, FALSE);

      // Inserir novos arquivos
      for ($i = 0; $i < $tot; $i++) {
        $todasQuery = mysqli_query($connect, "INSERT INTO arquivosDocumentacao (idDocumentacao, nomeArquivo) VALUES ('{$idDocumentacao}', '{$arrayUnico['name'][$i]}')");
        if (!$todasQuery) {
          break;
        }
      }

      if ($todasQuery) {
        $todasQuery = mysqli_query($connect, "UPDATE documentacao SET titulo = '$titulo', descricaoDoc = '$descricaoDoc', idArea = '$idArea' WHERE idDocumentacao = '$idDocumentacao'");
      }

      // Excluir os arquivos selecionados
      if ($todasQuery && isset($_POST['arquivos'])) {
        $arquivosExistentes = $_POST['arquivos'];
        foreach ($arquivosExistentes as $key => $value) {
          if (substr($value, 0, 7) == "******-") {
            $nomeArquivo = substr($value, 7, strlen($value));
            $todasQuery = mysqli_query($connect, "DELETE FROM arquivosDocumentacao WHERE idArquivo='$key'");
            if (!$todasQuery) {
              $estadoAnexos = false;
              break;
            } else {
              unlink($pastaDestino . '/' . $nomeArquivo);
            }
          }
        }
      }

      if ($todasQuery && is_dir($pastaDestino) && mysqli_commit($connect)) {
        for ($i = 0; $i < $tot; $i++) {
          move_uploaded_file($arrayUnico['tmp_name'][$i], $pastaDestino . $arrayUnico['name'][$i]);
        }
        $_SESSION['mensagem'] = ["texto" => "Documentação gerada com sucesso", "cor" => "alert-success"];
      } else {
        mysqli_rollback($connect);
        $_SESSION['mensagem'] = ["texto" => "Erro ao atualizar documentação", "cor" => "alert-danger"];
      }
    } else {
      $_SESSION['mensagem'] = ["texto" => "Arquivo(s) não suportado(s) ou inválido(s)", "cor" => "alert-danger"];
    }
  } else {
    $_SESSION['mensagem'] = ["texto" => "Desculpe tivemos um erro!", "cor" => "alert-danger"];
  }

  mysqli_close($connect);
  header('Location: ../viewDocumentation?id=' . $idDocumentacao);
}
