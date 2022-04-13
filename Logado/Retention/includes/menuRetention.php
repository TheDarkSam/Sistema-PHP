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
        <div class="sb-sidenav-menu-heading">Prazo de retenção</div>
        <?php if ($_SESSION['acesso']['retencao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newAreaRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
            Cadastro de área
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['retencao']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listAreaRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            Lista de áreas
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['retencao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newCategoriaRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
            Cadastro de categoria
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['retencao']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listCategoriaRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            Lista de categorias
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['retencao']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
            Cadastro prazo de retenção
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['retencao']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listRetention">
            <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
            Lista de prazo de retenção
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>
<!-- ./ MENU lateral -->