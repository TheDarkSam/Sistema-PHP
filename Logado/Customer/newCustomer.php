<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['incluir']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuCustomer.php';
?>

  <script>
    document.title = 'Cadastro de cliente';
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
              <i class="fas fa-users mr-1"></i>Novo cliente
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actNewCustomer" onsubmit="return validator()" name="formCustomer" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome_empresa">* Nome empresa</label>
                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome_responsavel">* Nome responsável</label>
                        <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="whatsapp">Whatsapp</label>
                        <input type="tel" class="form-control" id="whatsapp" name="whatsapp" minlength="15">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="celular">Telefone celular</label>
                        <input type="tel" class="form-control" id="celular" name="celular" minlength="15">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="telefone_fixo">Telefone Fixo</label>
                        <input type="tel" class="form-control" id="telefone_fixo" name="telefone_fixo" minlength="14" maxlength="14">
                      </div>
                    </div>

                    * Campos obrigatórios <br>
                    ** Preencha ao menos um contato

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnNewCustomer" name="btnNewCustomer">Cadastrar</button>
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