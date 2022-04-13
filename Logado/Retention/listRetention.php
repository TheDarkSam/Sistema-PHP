<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['visualizar']) :
  header('Location: ../../index');
else :
  require_once '../../conexao/sistemaConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';

  $arrayRetencao = array();

  $sql = "SELECT p.*, c.idCategoria, c.descCategoria, a.* FROM prazoRetencao p INNER JOIN areaRetencao a INNER JOIN categoriaRetencao c WHERE p.idCategoria = c.idCategoria AND c.idArea = a.idArea";

  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      array_push($arrayRetencao, $dados);
    }
  }

  mysqli_close($connect);
?>

  <script>
    document.title = "Lista de Prazos Retenção";
  </script>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Prazo de Retenção</h1>
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
              <i class="fas fa-calendar-day mr-1"></i>Lista de prazo retenção
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="table-responsive">
                  <table id="tableRetention" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Título</th>
                          <th>Área</th>
                          <th>Categoria</th>
                          <th>Destino</th>
                          <th>Prazo</th>
                          <th>Visualizar</th>
                          <th>Excluir</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($arrayRetencao as $retencao) :
                        ?>
                          <tr>
                            <td><?php echo "{$retencao['titulo']}"; ?></td>
                            <td><?php echo "{$retencao['descArea']}"; ?></td>
                            <td><?php echo "{$retencao['descCategoria']}"; ?></td>
                            <td><?php echo "{$retencao['destino']}"; ?></td>
                            <td><?php echo "{$retencao['dataPrazo']}"; ?></td>
                            <td class="text-center">
                              <a href="viewRetention?id=<?php echo "{$retencao['idRetencao']}"; ?>">
                                <button class="btn bg-transparent"><i class="fas fa-eye text-primary"></i></button>
                              </a>
                            </td>
                            

                            <?php if ($_SESSION['acesso']['retencao']['excluir'] == 1) : ?>
                              <td class="text-center">
                                <a data-toggle="modal" href="#modalDelete<?php echo $retencao['idRetencao']; ?>" class="text-danger">
                                  <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                                </a>
                              </td>

                              <div id="modalDelete<?php echo $retencao['idRetencao']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Excluir prazo: <?php echo $retencao['titulo']; ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Tem certeza que deseja excluir o prazo de rentencao: <?php echo $retencao['titulo']; ?>
                                    </div>
                                    <div class="modal-footer">
                                      <form action="php_action/actDeleteRetention" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $retencao['idRetencao']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                        <button type="submit" class="btn btn-danger" id="btnDeleteRetention" name="btnDeleteRetention">Excluir</button>
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
      $('#tableRetention').DataTable({
        "language": {
          "url": "../../plugins/datatables/js/dataTablesPortugues.json"
        }
      });
    });
  </script>

<?php endif; ?>