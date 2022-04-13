<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$_SESSION['acesso']['usuario']['visualizar']) :
  header('Location: ../../index');
else :
  require_once '../../conexao/usuarioConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuUser.php';

  $arrayUsuario = array();

  $sql = "SELECT * FROM usuario where idUsuario != '{$_SESSION['id_usuario']}'";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      array_push($arrayUsuario, $dados);
    }
  }

  mysqli_close($connect);
?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = 'Lista de usuários';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Usuários</h1>
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
              <i class="fas fa-users mr-1"></i>Lista de usuários
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
                          <th>Nome</th>
                          <th>Login</th>
                          <?php if ($_SESSION['acesso']['usuario']['editar'] == 1) : ?>
                            <th>Editar</th>
                          <?php endif; ?>
                          <?php if ($_SESSION['acesso']['usuario']['excluir'] == 1) : ?>
                            <th>Excluir</th>
                          <?php endif; ?>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        foreach ($arrayUsuario as $usuario) :
                        ?>
                          <tr>
                            <td><?php echo $usuario['nome']; ?></td>
                            <td><?php echo $usuario['login']; ?></td>

                            <?php if ($_SESSION['acesso']['usuario']['editar'] == 1) : ?>
                              <td class="text-center">
                                <a href="editUser?id=<?php echo $usuario['idUsuario']; ?>">
                                  <button class="btn bg-transparent"><i class="fas fa-edit text-primary"></i></button>
                                </a>
                              </td>
                            <?php endif; ?>

                            <?php if ($_SESSION['acesso']['usuario']['excluir'] == 1) : ?>
                              <td class="text-center">
                                <a data-toggle="modal" href="#modalDelete<?php echo $usuario['idUsuario']; ?>" class="text-danger">
                                  <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                                </a>
                              </td>

                              <div id="modalDelete<?php echo $usuario['idUsuario']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Excluir login: <?php echo $usuario['login']; ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Tem certeza que deseja excluir o login: <?php echo $usuario['login']; ?>
                                    </div>
                                    <div class="modal-footer">
                                      <form action="php_action/actDeleteUser" method="POST">
                                        <input type="hidden" name="login" value="<?php echo $usuario['login']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                        <button type="submit" class="btn btn-danger" id="btnDeleteUser" name="btnDeleteUser">Excluir</button>
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