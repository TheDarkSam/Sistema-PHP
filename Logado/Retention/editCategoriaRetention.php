<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';

// Sessão
session_start();

$sqlArea = "SELECT * FROM areaRetencao";
$resultArea = mysqli_query($connect, $sqlArea);
$arrayArea = array();
if (mysqli_num_rows($resultArea) > 0) {
  while ($area = mysqli_fetch_array($resultArea)) {
    array_push($arrayArea, array('idArea' => $area['idArea'], 'txt' => "{$area['descArea']}"));
  }
}

$existe = false;

if (isset($_POST['idCategoria'])) {
  $sql = "SELECT * from categoriaRetencao where idCategoria = {$_POST['idCategoria']}";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    $dados = mysqli_fetch_array($resultado);
    $existe = true;
  }
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
    document.title = "Editar categoria";
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
              <i class="fas fa-calendar-day mr-1"></i>Editar categoria
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
                  if ($arrayArea) :
                  ?>
                    <!-- form -->
                    <form action="php_action/actEditCategoriaRetention" method="POST">

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="area">* Área</label>
                          <select class="form-control " id="area" name="area" required>
                            <?php
                            foreach ($arrayArea as $area) :
                              $selected = $area['idArea'] == $dados['idArea'] ? "selected" : "";
                              echo "<option value=\"{$area['idArea']}\" {$selected}>{$area['txt']}</option>";
                            endforeach; ?>
                          </select>
                        </div>
                      </div>


                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="descArea">* Categoria</label>
                          <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo $dados['descCategoria']; ?>" required maxlength="40">
                        </div>
                      </div>

                      <input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $dados['idCategoria']; ?>">

                      * Campos obrigatórios

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="btnEditCategoria" name="btnEditCategoria">Editar Categoria</button>
                      </div>
                    </form>
                    <!-- /. form -->
                  <?php
                  else :
                    echo "Não existem áreas cadastrados";
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
  <script src="../../plugins/jquery/jquery.mask.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#area").select2();
    });
  </script>
<?php endif; ?>