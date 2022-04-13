<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['visualizar']) :
  header('Location: ../../index');
else :
  require_once '../../conexao/sistemaConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuCustomer.php';

  $arrayCliente = array();

  $sql = "SELECT c.*, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Whatsapp' AND t.idCliente = c.idCliente) AS whatsapp, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Celular' AND t.idCliente = c.idCliente) AS celular, (SELECT t.telefone FROM contato t WHERE t.tipo = 'Fixo' AND t.idCliente = c.idCliente) AS fixo FROM cliente c";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      array_push($arrayCliente, $dados);
    }
  }

  mysqli_close($connect);

?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = 'Lista de clientes';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Clientes</h1>
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
              <i class="fas fa-users mr-1"></i>Lista de clientes
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table id="tableUsers" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Empresa</th>
                          <th>Nome responsável</th>
                          <th>E-mail</th>
                          <th>Fixo</th>
                          <th>Celular</th>
                          <th>Whatsapp</th>
                          <?php if ($_SESSION['acesso']['cliente']['editar'] == 1) : ?>
                            <th>Editar</th>
                          <?php endif; ?>
                          <?php if ($_SESSION['acesso']['cliente']['excluir'] == 1) : ?>
                            <th>Excluir</th>
                          <?php endif; ?>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        foreach ($arrayCliente as $cliente) :
                          $idCliente = $cliente['idCliente'];
                        ?>
                          <tr>
                            <td><?php echo $cliente['nomeEmpresa']; ?></td>
                            <td><?php echo $cliente['nomeResponsavel']; ?></td>
                            <td><?php echo $cliente['email']; ?></td>
                            <td><?php echo $cliente['fixo']; ?></td>
                            <td><?php echo $cliente['celular'] ?></td>
                            <td><?php echo $cliente['whatsapp'] ?></td>

                            <?php if ($_SESSION['acesso']['cliente']['editar'] == 1) : ?>
                              <td class="text-center">
                                <a href="editCustomer?id=<?php echo $cliente['idCliente']; ?>">
                                  <button class="btn bg-transparent"><i class="fas fa-edit text-primary"></i></button>
                                </a>
                              </td>
                            <?php endif; ?>

                            <?php if ($_SESSION['acesso']['cliente']['excluir'] == 1) : ?>
                              <td class="text-center">
                                <a data-toggle="modal" href="#modalDelete<?php echo $cliente['idCliente']; ?>" class="text-danger">
                                  <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                                </a>
                              </td>

                              <div id="modalDelete<?php echo $cliente['idCliente']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Excluir cliente: <?php echo $cliente['nomeEmpresa']; ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Tem certeza que deseja excluir o cliente
                                    </div>
                                    <div class="modal-footer">
                                      <form action="php_action/actDeleteCustomer" method="POST">
                                        <input type="hidden" name="idCliente" value="<?php echo $cliente['idCliente']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                        <button type="submit" class="btn btn-danger" id="btnDeleteCustomer" name="btnDeleteCustomer">Excluir</button>
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
      $('#tableUsers').DataTable({
        "language": {
          "url": "../../plugins/datatables/js/dataTablesPortugues.json"
        }
      });
    });
  </script>

<?php endif; ?>