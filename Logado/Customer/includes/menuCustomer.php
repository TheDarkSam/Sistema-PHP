<!-- MENU lateral -->
<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Dashboard</div>
        <a class="nav-link" href="../Home/index">
          <div class="sb-nav-link-icon"><i class="fas fa-th"></i></div>
          Home
        </a>
        <div class="sb-sidenav-menu-heading">Clientes</div>
        <?php if ($_SESSION['acesso']['cliente']['incluir'] == 1) : ?>
        <!-- Item menu -->
        <a class="nav-link" href="newCustomer">
          <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
          Cadastro
        </a>
        <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['cliente']['visualizar'] == 1) : ?>
        <!-- Item menu -->
        <a class="nav-link" href="listCustomer">
          <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
          Lista
        </a>
        <!-- ./ Item menu -->
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>
<!-- ./ MENU lateral -->