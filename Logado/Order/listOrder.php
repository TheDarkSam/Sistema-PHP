<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';
require_once '../operations/Formats.php';

// Sessão
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['visualizar']) :
  header('Location: ../../index');
else :

  $condicao = "";
  if (isset($_GET['condicao'])) {
    if ($_GET['condicao'] == 'aprovado') {
      $condicao = "WHERE o.situacao = 'Aprovado'";
      $txtCondicao = "Orçamentos Aprovados";
    } elseif ($_GET['condicao'] == 'pendente') {
      $condicao = "WHERE o.situacao = 'Pendente'";
      $txtCondicao = "Orçamentos Pendentes";
    } elseif ($_GET['condicao'] == 'reprovado') {
      $condicao = "WHERE o.situacao = 'Reprovado'";
      $txtCondicao = "Orçamentos Reprovados";
    } else {
      header('Location: listOrder');
    }
  } else {
    $txtCondicao = "Todos os Orçamentos";
  }

  $formats = new Formats;

  $arrayOrcamento = array();

  $sql = "SELECT o.idOrcamento, c.nomeEmpresa, c.nomeResponsavel, o.valorHora, l.total, o.situacao FROM orcamento o INNER JOIN cliente c ON c.idCliente = o.idCliente INNER JOIN lgpd l on o.idOrcamento = l.idOrcamento {$condicao}";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) > 0) {
    while ($dados = mysqli_fetch_array($resultado)) {
      $aux[0] = $dados;
      $aux[0] += array('valor' => round($dados['valorHora'] / 60, 2) * $dados['total']);
      array_push($arrayOrcamento, $aux[0]);
    }
  }

  mysqli_close($connect);

  include_once '../includes/header.php';
  include_once 'includes/menuOrder.php';

?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = '<?php echo $txtCondicao;?>';
  </script>

  <main>
    <div class="container-fluid">
      <h1 class="mt-4 text-center">Orçamentos</h1>
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
              <i class="fas fa-edit mr-1"></i>Editar orçamento
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table id="tableOrcamentos" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Número</th>
                          <th>Cliente</th>
                          <th>Valor</th>
                          <th>Situação</th>
                          <th>Visualizar</th>
                          <?php if ($_SESSION['acesso']['orcamento']['excluir'] == 1) : ?>
                            <th>Excluir</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($arrayOrcamento as $orcamento) :
                        ?>
                          <tr>
                            <td><?php echo "{$orcamento['idOrcamento']}"; ?></td>
                            <td><?php echo "{$orcamento['nomeEmpresa']} - {$orcamento['nomeResponsavel']}"; ?></td>
                            <td><?php echo "{$formats->formatarDinheiro($orcamento['valor'])}"; ?></td>
                            <td><?php echo "{$orcamento['situacao']}"; ?></td>
                            <td class="text-center">
                              <a href="viewOrder?id=<?php echo "{$orcamento['idOrcamento']}"; ?>">
                                <button class="btn bg-transparent"><i class="fas fa-eye text-primary"></i></button>
                              </a>
                            </td>
                            <?php if ($_SESSION['acesso']['usuario']['excluir'] == 1) : ?>
                              <td class="text-center">
                                <a data-toggle="modal" href="#modalDelete<?php echo $orcamento['idOrcamento']; ?>" class="text-danger">
                                  <button type="submit" class="btn bg-transparent"><i class="fas fa-trash text-danger"></i></button>
                                </a>
                              </td>

                              <div id="modalDelete<?php echo $orcamento['idOrcamento']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Excluir orçamento: <?php echo $orcamento['idOrcamento']; ?></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      Tem certeza que deseja excluir o orçamento: <?php echo "{$orcamento['nomeEmpresa']} - {$orcamento['nomeResponsavel']}"; ?>
                                    </div>
                                    <div class="modal-footer">
                                      <form action="php_action/actDeleteOrder" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $orcamento['idOrcamento']; ?>">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                                        <button type="submit" class="btn btn-danger" id="btnDeleteOrder" name="btnDeleteOrder">Excluir</button>
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
      $('#tableOrcamentos').DataTable({
        "language": {
          "url": "../../plugins/datatables/js/dataTablesPortugues.json"
        }
      });
    });
  </script>

<?php endif; ?>