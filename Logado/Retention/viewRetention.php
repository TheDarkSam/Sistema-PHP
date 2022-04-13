<?php
session_start();

$existe = false;

if (isset($_GET['id'])) {
  $existe = true;
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['retencao']) || !$_SESSION['acesso']['retencao']['visualizar'] && !$existe) :
  header('Location: ../../index');
else :
  require_once '../../conexao/sistemaConnect.php';

  include_once '../includes/header.php';
  include_once 'includes/menuRetention.php';

  $arrayRetencao = array();
  $arrayArquivos = array();
  $sql = "SELECT p.*, c.descCategoria, a.descArea from prazoRetencao p INNER JOIN areaRetencao a INNER JOIN categoriaRetencao c ON p.idCategoria = c.idCategoria AND c.idArea = a.idArea where p.idRetencao = '{$_GET['id']}'";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    while ($dados = mysqli_fetch_array($resultado)) {
      $arrayRetencao += $dados;
    }

    $caminhoPasta = "../../arquivos/retention/{$arrayRetencao['nomePasta']}/";

    $sql2 = "SELECT nomeArquivo FROM arquivosRetencao WHERE idRetencao = '{$_GET['id']}'";
    $resultado2 = mysqli_query($connect, $sql2);
    if (mysqli_num_rows($resultado2) > 0) {
      while ($dados2 = mysqli_fetch_array($resultado2)) {
        array_push($arrayArquivos, $dados2);
      }
    }
  }


  mysqli_close($connect);
?>

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/css/dataTables.min.css">
  <link rel="stylesheet" href="../plugins/datatables/css/dataTablesBootstrap4.min.css">

  <script>
    document.title = "Visualizar Prazo de Retenção";
  </script>

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
              <i class="fas fa-calendar-day mr-1"></i>Prazo de Retenção
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <!-- Menu opções -->
                  <div class="dropdown text-right">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Opções
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                      <?php if ($_SESSION['acesso']['retencao']['editar'] == 1) : ?>
                        <form action="editRetention" method="POST">
                          <input type="hidden" name="idRetencao" value="<?php echo $_GET['id']; ?>">
                          <!-- Item -->
                          <button class="dropdown-item" type="submit" name='btnUpdate'>
                            <span class="fa fa-edit text-primary"></span> Editar Prazo
                          </button>
                          <!-- /. Item -->
                        </form>
                      <?php endif; ?>

                      <?php if ($_SESSION['acesso']['retencao']['excluir'] == 1) : ?>
                        <form action="php_action/actDeleteRetention" method="POST">
                          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                          <!-- Item -->
                          <button class="dropdown-item" type="submit" name='btnDeleteRetention'>
                            <span class="fas fa-trash text-danger"></span> Excluir Prazo
                          </button>
                          <!-- /. Item -->
                        </form>
                      <?php endif; ?>

                    </div>
                  </div>
                  <!-- /. Menu opções -->

                  <hr>
                  <div class="table-responsive">
                    <table id="tableRetention" class="table table-striped table-bordered table-hover text-center">
                      <thead class="thead-dark">
                        <tr>
                          <th>Campo</th>
                          <th>Dado</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Título</td>
                          <td><?php echo $arrayRetencao['titulo']; ?></td>
                        </tr>
                        <tr>
                          <td>Descrição</td>
                          <td><?php echo $arrayRetencao['descricao']; ?></td>
                        </tr>
                        <tr>
                          <td>Finalidade</td>
                          <td><?php echo $arrayRetencao['finalidade']; ?></td>
                        </tr>
                        <tr>
                          <td>Destino</td>
                          <td><?php echo $arrayRetencao['destino']; ?></td>
                        </tr>
                        <tr>
                          <td>Prazo</td>
                          <td><?php echo $arrayRetencao['dataPrazo']; ?></td>
                        </tr>
                        <tr>
                          <td>Link</td>
                          <td><a href="<?php echo $arrayRetencao['link']; ?>" target="_blank"><?php echo $arrayRetencao['link']; ?></a></td>
                        </tr>

                        <?php
                        $cont = 0;
                        foreach ($arrayArquivos as $arquivo) :
                          $cont++; ?>
                          <tr>
                            <td>Anexo <?php echo $cont; ?></td>
                            <td class="text-center">
                              <a href="<?php echo $caminhoPasta . $arquivo['nomeArquivo']; ?>" download="" title="<?php echo $arquivo['nomeArquivo']; ?>"><?php echo $arquivo['nomeArquivo']; ?></a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
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

<?php endif; ?>