<?php
// Sessão
session_start();

// Conexão
require_once '../../conexao/sistemaConnect.php';

$sql = "SELECT * FROM areaDoc ORDER BY descArea";
$resultadoArea = mysqli_query($connect, $sql);
$arrayArea = array();
if (mysqli_num_rows($resultadoArea) > 0) {
  while ($area = mysqli_fetch_array($resultadoArea)) {
    array_push($arrayArea, array('idArea' => $area['idArea'], 'txt' => "{$area['descArea']}"));
  }
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['documentacao']) || !$_SESSION['acesso']['documentacao']['incluir']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuDocumentation.php';
?>
  <!-- Seletc2 -->
  <link href="../../plugins/select2/select2.min.css" rel="stylesheet" />

  <script>
    document.title = "Nova documentação";
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Documentação</h1>
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
              <i class="fas fa-file mr-1"></i>Nova documentação
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">

                  <?php
                  // Clientes
                  if ($arrayArea) :
                  ?>
                    <!-- form -->
                    <form action="php_action/actNewDocumentation" method="POST" enctype="multipart/form-data">

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="titulo">* Título da documentação</label>
                          <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="40">
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="descricaoDoc">* Descrição</label>
                          <textarea type="text" class="form-control" id="descricaoDoc" name="descricaoDoc" required maxlength="400"></textarea>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12 select2-container--bootstrap4">
                          <label for="idArea">* Área</label>
                          <select class="form-control" id="idArea" name="idArea" required>
                            <?php
                            foreach ($arrayArea as $area) :
                              echo "<option value=\"{$area['idArea']}\">{$area['txt']}</option>";
                            endforeach; ?>
                          </select>
                        </div>
                      </div>

                      <div id="divAnexos" class="form-row">
                      </div>

                      * Arquivos suportados: Word, Excel, Powerpoint e PDF
                      <br>
                      * Campos obrigatórios

                      <div class="text-center">
                        <button type="button" id="btnNovoAnexo" class="btn btn-dark">Novo anexo</button>
                        <button type="submit" class="btn btn-primary" id="btnNewDocumentation" name="btnNewDocumentation">Cadastrar documentação</button>
                      </div>
                    </form>
                    <!-- /. form -->
                  <?php
                  else :
                    echo "Não existem áreas cadastradas";
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
  <script src="../../plugins/select2/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#idArea").select2();
      $('#idArea').val(null).trigger('change');
    });
  </script>
  <script type="text/javascript" src="js/newDocumentation.js"></script>
  <script>
    var qtdAnexos = 0;
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
  </script>

<?php endif; ?>