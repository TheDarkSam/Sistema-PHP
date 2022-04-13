<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';
require_once '../operations/Formats.php';

// Sessão
session_start();

$formats = new Formats;

$existe = false;
$arrayValores = array();

if (isset($_POST['idOrcamento'])) {
  $sql = "SELECT l.*, o.valorHora, c.nomeEmpresa FROM lgpd l INNER JOIN orcamento o ON l.idOrcamento = o.idOrcamento INNER JOIN cliente c ON c.idCliente = o.idCliente AND l.idOrcamento = '{$_POST['idOrcamento']}'";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    $dados = mysqli_fetch_array($resultado);
    $valorMinuto = round($dados['valorHora'] / 60, 2);
    $existe = true;

    $arrayValores['coleta'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['coleta']));
    $arrayValores['coleta'] += $formats->minutosHora($dados['coleta']);

    $arrayValores['gap'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['gap']));
    $arrayValores['gap'] += $formats->minutosHora($dados['gap']);

    $arrayValores['planoAcao'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['planoAcao']));
    $arrayValores['planoAcao'] += $formats->minutosHora($dados['planoAcao']);

    $arrayValores['implantacao'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['implantacao']));
    $arrayValores['implantacao'] += $formats->minutosHora($dados['implantacao']);

    $arrayValores['relatorio'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['relatorio']));
    $arrayValores['relatorio'] += $formats->minutosHora($dados['relatorio']);

    $arrayValores['treinamento'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['treinamento']));
    $arrayValores['treinamento'] += $formats->minutosHora($dados['treinamento']);

    $arrayValores['dpo'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['dpo']));
    $arrayValores['dpo'] += $formats->minutosHora($dados['dpo']);

    $arrayValores['validacao'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['validacao']));
    $arrayValores['validacao'] += $formats->minutosHora($dados['validacao']);

    $arrayValores['melhorias'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['melhorias']));
    $arrayValores['melhorias'] += $formats->minutosHora($dados['melhorias']);

    $arrayValores['total'] = array('valor' => $formats->formatarDinheiro($valorMinuto * $dados['total']));
    $arrayValores['total'] += $formats->minutosHora($dados['total']);
  }
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar'] || !$existe) :
  header('Location: ../../index');
else :

  include_once '../includes/header.php';
  include_once 'includes/menuOrder.php';
?>

  <link rel="stylesheet" href="style3.css">

  <script>
    document.title = 'Editar LGPD';
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
              <i class="fas fa-edit mr-1"></i>Editar LGPD
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">
                  <div class="col-md-8 offset-md-2">
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <h3>Cliente: <?php echo $dados['nomeEmpresa']; ?></h3>
                        <h5>Orçamento: <?php echo $_POST['idOrcamento']; ?></h5>
                      </div>
                    </div>
                  </div>

                  <hr>

                  <!-- form -->
                  <form action="php_action/actEditLgpd" method="POST">
                    <div class="table-responsive">
                      <table id="tableUsers" class="table table-striped table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Item</th>
                            <th>Horas</th>
                            <th>Minutos</th>
                            <th>Valor</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th>Coleta</th>
                            <td>
                              <input type="number" id="horaColeta" name="horaColeta" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['coleta']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minColeta" name="minColeta" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['coleta']['minuto']; ?>" required>
                            </td>

                            <td id="valorColeta"><?php echo $arrayValores['coleta']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Gap Analysis</th>
                            <td>
                              <input type="number" id="horaGap" name="horaGap" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['gap']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minGap" name="minGap" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['gap']['minuto']; ?>" required>
                            </td>

                            <td id="valorGap"><?php echo $arrayValores['gap']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Plano de Ação</th>
                            <td>
                              <input type="number" id="horaPlanoAcao" name="horaPlanoAcao" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['planoAcao']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minPlanoAcao" name="minPlanoAcao" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['planoAcao']['minuto']; ?>" required>
                            </td>

                            <td id="valorPlanoAcao"><?php echo $arrayValores['planoAcao']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Implantação</th>
                            <td>
                              <input type="number" id="horaImplantacao" name="horaImplantacao" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['implantacao']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minImplantacao" name="minImplantacao" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['implantacao']['minuto']; ?>" required>
                            </td>

                            <td id="valorImplantacao"><?php echo $arrayValores['implantacao']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Relatório de impacto</th>
                            <td>
                              <input type="number" id="horaRelatorio" name="horaRelatorio" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['relatorio']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minRelatorio" name="minRelatorio" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['relatorio']['minuto']; ?>" required>
                            </td>

                            <td id="valorRelatorio"><?php echo $arrayValores['relatorio']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Treinamento</th>
                            <td>
                              <input type="number" id="horaTreinamento" name="horaTreinamento" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['treinamento']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minTreinamento" name="minTreinamento" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['treinamento']['minuto']; ?>" required>
                            </td>

                            <td id="valorTreinamento"><?php echo $arrayValores['treinamento']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>DPO</th>
                            <td>
                              <input type="number" id="horaDpo" name="horaDpo" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['dpo']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minDpo" name="minDpo" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['dpo']['minuto']; ?>" required>
                            </td>

                            <td id="valorDpo"><?php echo $arrayValores['dpo']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Validação</th>
                            <td>
                              <input type="number" id="horaValidacao" name="horaValidacao" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['validacao']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minValidacao" name="minValidacao" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['validacao']['minuto']; ?>" required>
                            </td>

                            <td id="valorValidacao"><?php echo $arrayValores['validacao']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Melhorias</th>
                            <td>
                              <input type="number" id="horaMelhorias" name="horaMelhorias" class="formHoras" min="0" max="999" value="<?php echo $arrayValores['melhorias']['hora']; ?>" required>
                            </td>

                            <td>
                              <input type="number" id="minMelhorias" name="minMelhorias" class="formMinutos" min="0" max="59" value="<?php echo $arrayValores['melhorias']['minuto']; ?>" required>
                            </td>

                            <td id="valorMelhorias"><?php echo $arrayValores['melhorias']['valor']; ?></td>
                          </tr>

                          <tr>
                            <th>Total</th>
                            <td id="horaTotal"><?php echo $arrayValores['total']['hora']; ?></td>
                            <td id="minTotal"><?php echo $arrayValores['total']['minuto']; ?></td>
                            <td id="valorTotal"><?php echo $arrayValores['total']['valor']; ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <input type="hidden" id="valorMinuto" value="<?php echo $valorMinuto;?>">

                    <div class="text-center">
                      <input type="hidden" name="idOrcamento" value="<?php echo $dados['idOrcamento']; ?>">
                      <button type="submit" class="btn btn-primary" id="btnEditLgpd" name="btnEditLgpd">Salvar</button>
                      <a href="viewOrder?id=<?php echo $dados['idOrcamento']; ?>" class="btn btn-secondary text-white">Voltar</a>
                    </div>
                  </form>
                  <!-- /. form -->
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
  <script src="js/configEditLgpd.js"></script>

<?php endif; ?>