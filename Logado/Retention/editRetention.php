<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';

// Sessão
session_start();

$existe = false;

if (isset($_POST['idRetencao'])) {
  $sql = "SELECT p.*, c.*, a.* from prazoRetencao p INNER JOIN categoriaRetencao c ON p.idCategoria = c.idCategoria INNER JOIN areaRetencao a ON c.idArea = a.idArea WHERE p.idRetencao = {$_POST['idRetencao']}";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    $dados = mysqli_fetch_array($resultado);
    $existe = true;
  }
}

$arrayArquivos = array();
$sql2 = "SELECT * FROM arquivosRetencao WHERE idRetencao = '{$_POST['idRetencao']}'";
$resultado2 = mysqli_query($connect, $sql2);
if (mysqli_num_rows($resultado2) > 0) {
  while ($dados2 = mysqli_fetch_array($resultado2)) {
    array_push($arrayArquivos, $dados2);
  }
}

$sqlCategoria = "SELECT c.*, a.descArea FROM categoriaRetencao c INNER JOIN areaRetencao a WHERE c.idArea = a.idArea";
$resultCategoria = mysqli_query($connect, $sqlCategoria);
$arrayCategoria = array();
if (mysqli_num_rows($resultCategoria) > 0) {
  while ($categoria = mysqli_fetch_array($resultCategoria)) {
    array_push($arrayCategoria, array('id' => $categoria['idCategoria'], 'txt' => "{$categoria['descCategoria']} - {$categoria['descArea']}"));
  }
}

$sqlDestino = "SHOW COLUMNS FROM prazoRetencao LIKE 'destino'";
$resultDestino = mysqli_query($connect, $sqlDestino);
$arrayDestino = array();
if (mysqli_num_rows($resultDestino) > 0) {
  $enumDestino = mysqli_fetch_array($resultDestino)['Type'];

  // Retirar a string enum() da query
  $enumDestino = substr($enumDestino, 5, strlen($enumDestino));
  $enumDestino = substr($enumDestino, 0, strlen($enumDestino) - 1);
  $enumDestino = str_replace("'", "", $enumDestino);
  $arrayDestino = explode(",", $enumDestino);
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['editar'] || !$existe) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';
?>

  <!-- Seletc2 -->
  <link href="../../plugins/select2/select2.min.css" rel="stylesheet" />

  <script>
    document.title = "Editar prazo retenção";
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Prazo de retenção</h1>
      <!-- Espaço entre titulo e o conteudo-->
      <div class="mb-4"></div>

      <div class="row">
        <div class="col-lg-12">
          <!-- Aviso -->
          <?php
          if (isset($_SESSION['mensagem'])) : ?>
            <div class="alert <?php echo $_SESSION['mensagem']['cor'] ?> alert-dismissible fade show" role="alert">
              <?php echo $_SESSION['mensagem']['texto']; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            unset($_SESSION['mensagem']);
          endif; ?>
          <!-- /. Aviso -->
          <div class="card mb-4">
            <!-- card-header -->
            <div class="card-header">
              <i class="fas fa-calendar-day mr-1"></i>Editar prazo retenção
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <?php
                  // Area
                  if ($arrayCategoria) :
                  ?>
                    <!-- form -->
                    <form action="php_action/actEditRetention" method="POST" id="formEditRetention" enctype="multipart/form-data">

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="titulo">* Título</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $dados['titulo']; ?>" required maxlength="40">
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="descricao">* Descrição <sub>200 caracteres</sub></label>
                          <textarea type="text" class="form-control" id="descricao" name="descricao" required maxlength="200"><?php echo $dados['titulo']; ?></textarea>
                        </div>
                      </div>


                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="idCategoria">* Categoria</label>
                          <select class="form-control " id="idCategoria" name="idCategoria" required>
                            <?php
                            foreach ($arrayCategoria as $categoria) :
                              $selecinado = ($dados['idCategoria'] == $categoria['id']) ? "selected" : "";
                              echo "<option value=\"{$categoria['id']}\" {$selecinado}>{$categoria['txt']}</option>";
                            endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="finalidade">* Finalidade <sub>600 caracteres</sub></label>
                          <textarea type="text" class="form-control" id="finalidade" name="finalidade" required maxlength="600" rows="5"><?php echo $dados['finalidade']; ?></textarea>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="destino">* Destino</label>
                          <select class="form-control " id="destino" name="destino" required>
                            <?php
                            foreach ($arrayDestino as $destino) :
                              $selecinado = ($dados['destino'] == $destino) ? "selected" : "";
                              echo "<option value=\"{$destino}\" {$selecinado}>{$destino}</option>";
                            endforeach; ?>
                          </select>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="prazo">* Prazo</label>
                          <?php
                          $bloqueado = "";
                          if ($dados['dataPrazo'] == "Indefinido" || $dados['dataPrazo'] == "Permanente") {
                            $bloqueado = "readonly";
                          } ?>
                          <input type="text" class="form-control" id="prazo" name="prazo" value="<?php echo $dados['dataPrazo']; ?>" required maxlength="20" <?php echo $bloqueado; ?>>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="link">Link</label>
                          <input type="url" class="form-control" id="link" name="link" maxlength="2048" value="<?php echo $dados['link']; ?>">
                        </div>
                      </div>

                      <div id="divAnexos" class="form-row">
                        <?php
                        $cont = 0;
                        foreach ($arrayArquivos as $arquivo) :
                          $cont++;
                        ?>
                          <?php $idDiv = "divAnexo{$arquivo['idArquivo']}"; ?>
                          <?php $idInput = "anexo{$arquivo['idArquivo']}"; ?>

                          <div class="form-group col-md-6" id="<?php echo $idDiv; ?>">
                            <label for="<?php echo $idInput; ?>">* Anexo </label>
                            <i class="fa fa-trash text-danger" onclick="mudarEstadoInput('<?php echo $idInput; ?>'); destruirInputFile('<?php echo $idDiv; ?>');"></i>
                            <p><?php echo $arquivo['nomeArquivo']; ?></p>
                          </div>
                          <input type="hidden" name="arquivos[<?php echo "{$arquivo['idArquivo']}"; ?>]" id="<?php echo $idInput; ?>" value="<?php echo $arquivo['nomeArquivo']; ?>">

                        <?php endforeach; ?>
                      </div>

                      <input type="hidden" name="idRetencao" value="<?php echo $_POST['idRetencao']; ?>">

                      * Arquivos suportados: Word, Excel, Powerpoint e PDF
                      <br>
                      * Campos obrigatórios

                      <div class="text-center">
                        <button type="button" id="btnNovoAnexo" class="btn btn-dark">Novo anexo</button>
                        <button type="submit" class="btn btn-primary" id="btnEditRetention" name="btnEditRetention">Atualizar Prazo</button>
                      </div>
                    </form>
                    <!-- /. form -->

                  <?php
                  else :
                    echo "Não existem categorias cadastradas";
                  endif;
                  ?>
                </div>
                <!-- coluna -->
              </div>
              <!-- ./ row -->
            </div>
            <!-- ./ card-body -->
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include_once '../includes/footer.php'; ?>

  <script>
    document.getElementById('btnEditRetention').onclick = function() {
      let link = document.getElementById('link');

      if (link.value != '' && !link.value.includes("https://") && !link.value.includes("http://")) {
        link.value = 'https://' + link.value;
      }

    }
  </script>

  <script src="../../plugins/select2/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#idCategoria").select2();
      $("#destino").select2();
    });
  </script>
  <script type="text/javascript" src="js/newRetention.js"></script>

  <script>
    var qtdAnexos = <?php echo $cont; ?>;
    let divAnexos = document.getElementById('divAnexos');
    let btnNovoAnexo = document.getElementById('btnNovoAnexo');
    btnNovoAnexo.onclick = function() {
      if (qtdAnexos < 10) {
        qtdAnexos++;

        let novaDiv = document.createElement('div');
        novaDiv.setAttribute('class', 'form-group col-md-6');
        novaDiv.setAttribute('id', `divAnexo${qtdAnexos}`);

        let novaLabel = document.createElement('label');
        novaLabel.innerText = `* Anexo `;
        novaDiv.appendChild(novaLabel);

        let novoLink = document.createElement('a');
        novoLink.setAttribute('class', 'text-danger');
        novoLink.setAttribute('onclick', `destruirInputFile("divAnexo${qtdAnexos}")`);

        let novoIcone = document.createElement('i');
        novoIcone.setAttribute('class', 'fa fa-trash');
        novoLink.appendChild(novoIcone);

        novaLabel.appendChild(novoLink);

        let novoInput = document.createElement('input');

        novoInput.setAttribute('type', 'file');
        novoInput.setAttribute('class', 'form-control');
        novoInput.setAttribute('id', `anexo${qtdAnexos}`);
        novoInput.setAttribute('name', `anexos[]`);
        novoInput.required = true;
        novoInput.setAttribute('accept', '.doc, .docx, .xlsx, .xls, .ppt, .pptx, .pdf');
        novaDiv.appendChild(novoInput);

        divAnexos.appendChild(novaDiv);
      } else {
        alert('Limite de anexos');
      }
    }

    function destruirInputFile(id) {
      document.getElementById('divAnexos').removeChild(document.getElementById(id));
      qtdAnexos--;
    }

    function mudarEstadoInput(idInput) {
      document.getElementById(idInput).value = "******-" + document.getElementById(idInput).value;
    }
  </script>
<?php endif; ?>