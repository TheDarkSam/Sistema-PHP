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
        <div class="sb-sidenav-menu-heading">Usuários</div>
        <?php if ($_SESSION['acesso']['usuario']['incluir'] == 1) : ?>
        <!-- Item menu -->
        <a class="nav-link" href="newUser">
          <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
          Cadastro
        </a>
        <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['usuario']['visualizar'] == 1) : ?>
        <!-- Item menu -->
        <a class="nav-link" href="listUser">
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