<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['visualizar']) :
  header('Location: ../../index');
else :
  require_once '../../conexao/sistemaConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';

  $arrayCategoria = array();

  $sql = "SELECT a.descArea, c.* FROM categoriaRetencao c INNER JOIN areaRetencao a ON a.idArea = c.idArea";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      array_push($arrayCategoria, $dados);
    }
  }

  mysqli_close($connect);
?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = 'Lista de categorias';
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
              <i class="fas fa-calendar-day mr-1"></i>Lista de categorias
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table id="tableCategoriaRetencao" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Categoria</th>
                          <th>Área</th>
                          <th>Editar</th>
                          <th>Excluir</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($arrayCategoria as $categoria) :
                        ?>
                          <tr>
                            <td><?php echo "{$categoria['descCategoria']}"; ?></td>
                            <td><?php echo "{$categoria['descArea']}"; ?></td>

                            <td class="text-center">
                              <form action="editCategoriaRetention" method="POST">
                                <input type="hidden" name="idCategoria" value="<?php echo $categoria['idCategoria']; ?>">
                                <button class="btn bg-transparent"><i class="fa fa-edit text-primary"></i></button>
                              </form>
                            </td>

                            <td class="text-center">
                              <a data-toggle="modal" href="#modalDelete<?php echo $categoria['idCategoria']; ?>" class="text-danger">
                                <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                              </a>
                            </td>

                            <div id="modalDelete<?php echo $categoria['idCategoria']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Excluir categoria: <?php echo $categoria['descCategoria']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    Todas os prazos de retenção relacionadas também serão excluidas!
                                  </div>
                                  <div class="modal-footer">
                                    <form action="php_action/actDeleteCategoriaRetention" method="POST">
                                      <input type="hidden" name="idCategoria" value="<?php echo $categoria['idCategoria']; ?>">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                      <button type="submit" class="btn btn-danger" id="btnDeleteCategoria" name="btnDeleteCategoria">Excluir</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
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
      $('#tableCategoriaRetencao').DataTable({
        "language": {
          "url": "../../plugins/datatables/js/dataTablesPortugues.json"
        }
      });
    });
  </script>

<?php endif; ?>