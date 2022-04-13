<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['visualizar']) :
  header('Location: ../../index');
else :
  require_once '../../conexao/sistemaConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';

  $arrayArea = array();

  $sql = "SELECT * FROM areaRetencao";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      array_push($arrayArea, $dados);
    }
  }

  mysqli_close($connect);
?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = 'Lista de áreas';
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
              <i class="fas fa-calendar-day mr-1"></i>Lista de áreas
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table id="tableRetencao" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th class="text-center">Área</th>
                          <?php if ($_SESSION['acesso']['retencao']['editar'] == 1) : ?>
                            <th>Editar</th>
                          <?php endif; ?>
                          <?php if ($_SESSION['acesso']['retencao']['excluir'] == 1) : ?>
                            <th>Excluir</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($arrayArea as $area) :
                        ?>
                          <tr>
                            <td><?php echo "{$area['descArea']}"; ?></td>

                            <?php if ($_SESSION['acesso']['retencao']['editar'] == 1) : ?>
                              <td class="text-center">
                                <form action="editAreaRetention" method="POST">
                                  <input type="hidden" name="idArea" value="<?php echo $area['idArea']; ?>">
                                  <button class="btn bg-transparent"><i class="fa fa-edit text-primary"></i></button>
                                </form>
                              </td>
                            <?php endif; ?>

                            <?php if ($_SESSION['acesso']['retencao']['excluir'] == 1) : ?>
                              <td class="text-center">
                                <a data-toggle="modal" href="#modalDelete<?php echo $area['idArea']; ?>" class="text-danger">
                                  <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                                </a>
                              </td>

                              <div id="modalDelete<?php echo $area['idArea']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Excluir Área: <?php echo $area['descArea']; ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Todas as categorias e prazos de retenção relacionadas também serão excluidas!
                                    </div>
                                    <div class="modal-footer">
                                      <form action="php_action/actDeleteAreaRetention" method="POST">
                                        <input type="hidden" name="idArea" value="<?php echo $area['idArea']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                        <button type="submit" class="btn btn-danger" id="btnDeleteArea" name="btnDeleteArea">Excluir</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php endif; ?>
                          </tr>

                        <?php
                        endforeach;
                        ?>
                      </tbody>

                    </table>
                  </div>

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
  <!-- DataTables -->
  <script src="../../plugins/datatables/js/dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#tableRetencao').DataTable({
        "language": {
          "url": "../../plugins/datatables/js/dataTablesPortugues.json"
        }
      });
    });
  </script>

<?php endif; ?>