<?php
// Sessão
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['incluir']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';
?>

  <script>
    document.title = "Nova área";
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
              <i class="fas fa-calendar-day mr-1"></i>Nova área
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actNewAreaRetention" name="formCustomer" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="descArea">* Título da área</label>
                        <input type="text" class="form-control" id="descArea" name="descArea" required maxlength="100">
                      </div>
                    </div>

                    * Campos obrigatórios

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnNewArea" name="btnNewArea">Cadastrar</button>
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