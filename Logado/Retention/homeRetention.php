<?php
session_start();
// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || (!$_SESSION['acesso']['retencao']['visualizar'] && !$_SESSION['acesso']['retencao']['incluir'])) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php'; ?>

  <script>
    document.title = "Prazo de retenção";
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Prazo de retenção</h1>
      <!-- Espaço entre titulo e o conteudo-->
      <div class="mb-4"></div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-4">
            <!-- card-header -->
            <div class="card-header">
              <i class="fas fa-calendar-day mr-1"></i>Prazo de retenção
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  Seção de Prazo de retenção
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