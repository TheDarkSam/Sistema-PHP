<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';

// Sessão
session_start();

$existe = false;

if (isset($_POST['idOrcamento'])) {
  $sql = "SELECT o.*, c.idCliente, c.nomeEmpresa FROM orcamento o INNER JOIN cliente c ON o.idCliente = c.idCliente AND o.idOrcamento = '{$_POST['idOrcamento']}'";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    $dados = mysqli_fetch_array($resultado);
    $existe = true;

    // Verifica quais radios buttons devem ser selecionados
    $arrayRadioBtn = array();

    $arrayRadioBtn['tipoDados'] = array('pessoais' => '', 'sensiveis' => '');
    $arrayRadioBtn['tipoDados'][$dados['tipoDados']] = 'checked';

    $arrayRadioBtn['ti'] = array('sim' => '', 'nao' => '');
    $arrayRadioBtn['ti'][$dados['ti']] = 'checked';

    $arrayRadioBtn['juridico'] = array('sim' => '', 'nao' => '');
    $arrayRadioBtn['juridico'][$dados['juridico']] = 'checked';

    $arrayRadioBtn['politica'] = array('sim' => '', 'nao' => '');
    $arrayRadioBtn['politica'][$dados['politica']] = 'checked';

    $arrayRadioBtn['ferramentas'] = array('alto' => '', 'medio' => '', 'baixo' => '');
    $arrayRadioBtn['ferramentas'][$dados['ferramentas']] = 'checked';

    $arrayRadioBtn['observacoes'] = array('sim' => '', 'nao' => '');
    $arrayRadioBtn['observacoes'][$dados['observacoes']] = 'checked';
  }
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['editar']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuOrder.php';
?>

  <script>
    document.title = 'Editar orçamento';
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
                <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">

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
                  <form action="php_action/actEditOrder" method="POST">

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="departamentos">* Total departamentos</label>
                        <input type="number" class="form-control" id="departamentos" name="departamentos" min="0" value="<?php echo $dados['departamentos']; ?>" required>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="compartilhamentos">* Total compartilhamentos</label>
                        <input type="number" class="form-control" id="compartilhamentos" name="compartilhamentos" min="0" value="<?php echo $dados['compartilhamentos']; ?>" required>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="funcInternos">* Funcionários internos</label>
                        <input type="number" class="form-control" id="funcInternos" name="funcInternos" min="0" value="<?php echo $dados['funcInternos']; ?>" required>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="funcExternos">* Funcionários externos</label>
                        <input type="number" class="form-control" id="funcExternos" name="funcExternos" min="0" value="<?php echo $dados['funcExternos']; ?>" required>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="dispositivos">* Total dispositivos</label>
                        <input type="number" class="form-control" id="dispositivos" name="dispositivos" min="0" value="<?php echo $dados['dispositivos']; ?>" required>
                      </div>

                      <div class="form-group col-md-6">
                        <label for="sistemas">* Total sistemas</label>
                        <input type="number" class="form-control" id="sistemas" name="sistemas" min="0" value="<?php echo $dados['sistemas']; ?>" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Tipo de dados</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="tipoDados" id="dadosPessoais" value="pessoais" <?php echo $arrayRadioBtn['tipoDados']['pessoais']; ?> required>
                              <label class="form-check-label" for="dadosPessoais">
                                Pessoais
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="tipoDados" id="dadosSensiveis" value="sensiveis" <?php echo $arrayRadioBtn['tipoDados']['sensiveis']; ?> required>
                              <label class="form-check-label" for="dadosSensiveis">
                                Sensíveis
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Possui T.I.</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="ti" id="tiSim" value="sim" <?php echo $arrayRadioBtn['ti']['sim']; ?> required>
                              <label class="form-check-label" for="tiSim">
                                Sim
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="ti" id="tiNao" value="nao" <?php echo $arrayRadioBtn['ti']['nao']; ?> required>
                              <label class="form-check-label" for="tiNao">
                                Não
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Possui jurídico</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="juridico" id="juridicoSim" value="sim" <?php echo $arrayRadioBtn['juridico']['sim']; ?> required>
                              <label class="form-check-label" for="juridicoSim">
                                Sim
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="juridico" id="juridicoNao" value="nao" <?php echo $arrayRadioBtn['juridico']['nao']; ?> required>
                              <label class="form-check-label" for="juridicoNao">
                                Não
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Políticas de segurança</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="politica" id="politicaSim" value="sim" <?php echo $arrayRadioBtn['politica']['sim']; ?> required>
                              <label class="form-check-label" for="politicaSim">
                                Sim
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="politica" id="politicaNao" value="nao" <?php echo $arrayRadioBtn['politica']['nao']; ?> required>
                              <label class="form-check-label" for="politicaNao">
                                Não
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Ferramentas</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasAlto" value="alto" <?php echo $arrayRadioBtn['ferramentas']['alto']; ?> required>
                              <label class="form-check-label" for="ferramentasAlto">
                                Alto
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasMedio" value="medio" <?php echo $arrayRadioBtn['ferramentas']['medio']; ?> required>
                              <label class="form-check-label" for="ferramentasMedio">
                                Médio
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasBaixo" value="baixo" <?php echo $arrayRadioBtn['ferramentas']['baixo']; ?> required>
                              <label class="form-check-label" for="ferramentasBaixo">
                                Baixo
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6 text-center">
                        <div class="caixaForm">
                          <label class="col-form-label">* Observações</label>
                          <div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="observacoes" id="obsSim" value="sim" <?php echo $arrayRadioBtn['observacoes']['sim']; ?> required>
                              <label class="form-check-label" for="obsSim">
                                Sim
                              </label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="observacoes" id="obsNao" value="nao" <?php echo $arrayRadioBtn['observacoes']['nao']; ?> required>
                              <label class="form-check-label" for="obsNao">
                                Não
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <input type="hidden" name="idOrcamento" value="<?php echo $_POST['idOrcamento']; ?>">

                    * Campos obrigatórios

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnEditOrder" name="btnEditOrder">Salvar</button>
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

<?php endif; ?>