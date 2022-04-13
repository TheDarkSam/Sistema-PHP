<?php
session_start();
// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || (!$_SESSION['acesso']['usuario']['visualizar'] && !$_SESSION['acesso']['usuario']['incluir'])) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php'; ?>
  <?php include_once 'includes/menuUser.php'; ?>

  <script>
    document.title = 'Usuários';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Usuários</h1>
      <!-- Espaço entre titulo e o conteudo-->
      <div class="mb-4"></div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-4">
            <!-- card-header -->
            <div class="card-header">
              <i class="fas fa-users mr-1"></i>Usuários
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  Seção de usuários
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