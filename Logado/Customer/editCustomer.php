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

if (isset($_GET['id'])) {
  $idCliente = clear($_GET['id']);
  $sql = "SELECT c.*, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Whatsapp' AND t.idCliente = c.idCliente) AS whatsapp, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Celular' AND t.idCliente = c.idCliente) AS celular, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Fixo' AND t.idCliente = c.idCliente) AS fixo FROM cliente c WHERE c.idCliente = '{$idCliente}'";
  $resultado = mysqli_query($connect, $sql);

  if (mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_array($resultado);
    $existe = true;
  }
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['editar'] || !$existe) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuCustomer.php';
?>

  <script>
    document.title = 'Editar cliente';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Clientes</h1>
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
              <i class="fas fa-users mr-1"></i>Editar cliente
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actEditCustomer" onsubmit="return validator()" name="formCustomer" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome_empresa">* Nome empresa</label>
                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" value="<?php echo $dados['nomeEmpresa']; ?>" required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome_responsavel">* Nome responsável</label>
                        <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" value="<?php echo $dados['nomeResponsavel']; ?>" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $dados['email']; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="whatsapp">Whatsapp</label>
                        <input type="tel" class="form-control" id="whatsapp" name="whatsapp" minlength="15" value="<?php echo $dados["whatsapp"]; ?>">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="celular">Telefone celular</label>
                        <input type="tel" class="form-control" id="celular" name="celular" minlength="15" value="<?php echo $dados["celular"]; ?>">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="telefone_fixo">Telefone Fixo</label>
                        <input type="tel" class="form-control" id="telefone_fixo" name="telefone_fixo" minlength="14" maxlength="14" value="<?php echo $dados["fixo"]; ?>">
                      </div>
                    </div>

                    * Campos obrigatórios <br>
                    ** Preencha ao menos um contato

                    <input type="hidden" name="idCliente" id="idCliente" value="<?php echo $dados['idCliente']; ?>">

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnEditCustomer" name="btnEditCustomer">Editar</button>
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

  <script src="js/config.js"></script>
  <script src="../../plugins/jquery/jquery.mask.min.js"></script>
  <script type="text/javascript">
    $("#telefone_fixo").mask("(00) 0000-0000");
    $("#celular").mask("(00) 00000-0000");
    $("#whatsapp").mask("(00) 00000-0000");
  </script>

<?php endif; ?>