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
        <div class="sb-sidenav-menu-heading">Documentação</div>
        <?php if ($_SESSION['acesso']['documentacao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newAreaDocumentation">
            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
            Cadastro de área
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['documentacao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listAreaDocumentation">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            Lista de áreas
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['documentacao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newDocumentation">
            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
            Cadastro de documentação
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['documentacao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listDocumentation">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            Lista de documentação
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>
<!-- ./ MENU lateral -->