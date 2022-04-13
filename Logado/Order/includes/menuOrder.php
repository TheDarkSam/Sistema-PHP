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
        <div class="sb-sidenav-menu-heading">Orçamentos</div>
        <?php if ($_SESSION['acesso']['orcamento']['incluir'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="newOrder">
            <div class="sb-nav-link-icon"><i class="fas fa-plus-square"></i></div>
            Novo orçamento
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['orcamento']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listOrder?condicao=aprovado">
            <div class="sb-nav-link-icon"><i class="fas fa-check"></i></div>
            Aprovados
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['orcamento']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listOrder?condicao=pendente">
            <div class="sb-nav-link-icon"><i class="fas fa-exclamation"></i></div>
            Pendentes
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>

        <?php if ($_SESSION['acesso']['orcamento']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listOrder?condicao=reprovado">
            <div class="sb-nav-link-icon"><i class="fas fa-times"></i></div>
            Reprovados
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>        

        <?php if ($_SESSION['acesso']['orcamento']['visualizar'] == 1) : ?>
          <!-- Item menu -->
          <a class="nav-link" href="listOrder">
            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
            Todos
          </a>
          <!-- ./ Item menu -->
        <?php endif; ?>
      </div>
    </div>
  </nav>
</div>
<!-- ./ MENU lateral -->