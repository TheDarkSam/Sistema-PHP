<?php
session_start();
// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || (!$_SESSION['acesso']['orcamento']['visualizar'] && !$_SESSION['acesso']['orcamento']['incluir'])) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php'; ?>
  <?php include_once 'includes/menuOrder.php'; ?>

  <script>
    document.title = 'Clientes';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Orçamentos</h1>
      <!-- Espaço entre titulo e o conteudo-->
      <div class="mb-4"></div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-4">
            <!-- card-header -->
            <div class="card-header">
              <i class="fas fa-users mr-1"></i>Orçamentos
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  Seção de orçamentos
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

<?php include_once '../includes/footer.php';
endif; ?>