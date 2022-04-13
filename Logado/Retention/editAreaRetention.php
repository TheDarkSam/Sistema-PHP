<?php
session_start();

// Conexão
require_once '../../conexao/sistemaConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

$existe = false;

if (isset($_POST['idArea'])) {
  $sql = "SELECT * from areaRetencao where idArea = {$_POST['idArea']}";
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

  <script>
    document.title = "Editar Área";
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
              <i class="fas fa-chart-area mr-1"></i>Editar área
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actEditAreaRetention" name="formCustomer" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="descArea">* Novo Título da área: <?php echo $dados['descArea'];?></label>
                        <input type="text" class="form-control" id="descArea" name="descArea" value="<?php echo $dados['descArea'];?>"required maxlength="100">
                      </div>
                    </div>

                    * Campos obrigatórios

                    <input type="hidden" id="idArea" name="idArea" value="<?php echo $dados['idArea']; ?>">

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnEditArea" name="btnEditArea">Atualizar</button>
                    </div>
                  </form>
                  <!-- /. form -->
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
<?php endif; ?>