<?php
session_start();

include_once '../includes/header.php'; ?>

<link rel="stylesheet" href="../../dist/logado/css/home.css">

<script>
  document.title = 'Serviços';

  // Esconder Menu na HOME
  document.getElementsByTagName('body')[0].setAttribute('class', 'sb-nav-fixed sb-sidenav-toggled');
  document.getElementById('nav').removeChild(document.getElementById('sidebarToggle'));
  // ./ Esconder Menu na HOME -->
</script>

<main>
  <div class="container-fluid">
    <h1 class="mt-4 text-center">Serviços</h1>
    <!-- Espaço entre titulo e o conteudo -->
    <div class="mb-4"></div>

    <div class="row">
      <div class="col-xl-12">
        <div class="card mb-4">
          <!-- card-header -->
          <div class="card-header">
            <i class="fas fa-chart-area mr-1"></i>Serviços The Forense
          </div>
          <!-- ./ card-header -->
          <!-- card-body -->
          <div class="card-body">
            <!-- row -->
            <div class="row">

              <?php if (isset($_SESSION['acesso']['usuario']) && ($_SESSION['acesso']['usuario']['visualizar'] || $_SESSION['acesso']['usuario']['incluir'])) : ?>
                <!-- coluna -->
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <!-- card -->
                  <div class="card">
                    <img src="../../dist/logado/imgs/servicos/usuario.png" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title text-center">Usuários</h5>
                      <div class="text-center">
                        <a href="../User/homeUser" class="btn btn-primary">Abrir</a>
                      </div>
                    </div>
                  </div>
                  <!-- ./ card -->
                </div>
                <!-- coluna -->
              <?php endif; ?>

              <?php if (isset($_SESSION['acesso']['cliente']) && ($_SESSION['acesso']['cliente']['visualizar'] || $_SESSION['acesso']['cliente']['incluir'])) : ?>
                <!-- coluna -->
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <!-- card -->
                  <div class="card">
                    <img src="../../dist/logado/imgs/servicos/cliente.png" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title text-center">Clientes</h5>
                      <div class="text-center">
                        <a href="../Customer/homeCustomer" class="btn btn-primary">Abrir</a>
                      </div>
                    </div>
                  </div>
                  <!-- ./ card -->
                </div>
                <!-- coluna -->
              <?php endif; ?>

              <?php if (isset($_SESSION['acesso']['orcamento']) && ($_SESSION['acesso']['orcamento']['visualizar'] || $_SESSION['acesso']['orcamento']['incluir'])) : ?>
                <!-- coluna -->
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <!-- card -->
                  <div class="card">
                    <img src="../../dist/logado/imgs/servicos/orcamento.png" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title text-center">Orçamentos</h5>
                      <div class="text-center">
                        <a href="../Order/homeOrder" class="btn btn-primary">Abrir</a>
                      </div>
                    </div>
                  </div>
                  <!-- ./ card -->
                </div>
                <!-- coluna -->
              <?php endif; ?>

              <?php if (isset($_SESSION['acesso']['documentacao']) && ($_SESSION['acesso']['documentacao']['visualizar'] || $_SESSION['acesso']['documentacao']['incluir'])) : ?>
                <!-- coluna -->
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <!-- card -->
                  <div class="card">
                    <img src="../../dist/logado/imgs/servicos/documentacao.png" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title text-center">Documentação</h5>
                      <div class="text-center">
                        <a href="../Documentation/homeDocumentation" class="btn btn-primary">Abrir</a>
                      </div>
                    </div>
                  </div>
                  <!-- ./ card -->
                </div>
                <!-- coluna -->
              <?php endif; ?>

              <?php if (isset($_SESSION['acesso']['retencao']) && ($_SESSION['acesso']['retencao']['visualizar'] || $_SESSION['acesso']['retencao']['incluir'])) : ?>
                <!-- coluna -->
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                  <!-- card -->
                  <div class="card">
                    <img src="../../dist/logado/imgs/servicos/prazoRetencao.png" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title text-center">Prazo retenção</h5>
                      <div class="text-center">
                        <a href="../Retention/homeRetention" class="btn btn-primary">Abrir</a>
                      </div>
                    </div>
                  </div>
                  <!-- ./ card -->
                </div>
                <!-- coluna -->
              <?php endif; ?>

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