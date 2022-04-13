<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';

// Sessão
session_start();

$sqlCliente = "SELECT * FROM cliente";
$resultCliente = mysqli_query($connect, $sqlCliente);
$arrayCliente = array();
if (mysqli_num_rows($resultCliente) > 0) {
  while ($cliente = mysqli_fetch_array($resultCliente)) {
    array_push($arrayCliente, array('idCliente' => $cliente['idCliente'], 'txt' => "{$cliente['nomeEmpresa']} - {$cliente['nomeResponsavel']}"));
  }
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['cliente']) || !$_SESSION['acesso']['cliente']['incluir']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuOrder.php';
?>

  <!-- Seletc2 -->
  <link href="../../plugins/select2/select2.min.css" rel="stylesheet" />

  <script>
    document.title = 'Cadastro de orçamento';
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
              <i class="fas fa-users mr-1"></i>Novo orçamento
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">

                  <?php
                  // Clientes
                  if ($arrayCliente) :
                  ?>
                    <!-- form -->
                    <form action="php_action/actNewOrder" method="POST" onsubmit="return validator()">
                      <div class="form-row">
                        <div class="form-group col-md-12 select2-container--bootstrap4">
                          <label for="cliente">* Cliente</label>
                          <div class="select2-container--bootstrap4">
                            <select class="form-control " id="cliente" name="cliente" required>
                              <?php
                              foreach ($arrayCliente as $cliente) :
                                echo "<option value=\"{$cliente['idCliente']}\">{$cliente['txt']}</option>";
                              endforeach; ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="departamentos">* Total departamentos</label>
                          <input type="number" class="form-control" id="departamentos" name="departamentos" min="0" required>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="compartilhamentos">* Total compartilhamentos</label>
                          <input type="number" class="form-control" id="compartilhamentos" name="compartilhamentos" min="0" required>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="funcInternos">* Funcionários internos</label>
                          <input type="number" class="form-control" id="funcInternos" name="funcInternos" min="0" required>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="funcExternos">* Funcionários externos</label>
                          <input type="number" class="form-control" id="funcExternos" name="funcExternos" min="0" required>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="dispositivos">* Total dispositivos</label>
                          <input type="number" class="form-control" id="dispositivos" name="dispositivos" min="0" required>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="sistemas">* Total sistemas</label>
                          <input type="number" class="form-control" id="sistemas" name="sistemas" min="0" required>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6 text-center">
                          <div class="caixaForm">
                            <label class="col-form-label">* Tipo de dados</label>
                            <div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDados" id="dadosPessoais" value="pessoais" required>
                                <label class="form-check-label" for="dadosPessoais">
                                  Pessoais
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipoDados" id="dadosSensiveis" value="sensiveis" required>
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
                                <input class="form-check-input" type="radio" name="ti" id="tiSim" value="sim" required>
                                <label class="form-check-label" for="tiSim">
                                  Sim
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ti" id="tiNao" value="nao" required>
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
                                <input class="form-check-input" type="radio" name="juridico" id="juridicoSim" value="sim" required>
                                <label class="form-check-label" for="juridicoSim">
                                  Sim
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="juridico" id="juridicoNao" value="nao" required>
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
                                <input class="form-check-input" type="radio" name="politica" id="politicaSim" value="sim" required>
                                <label class="form-check-label" for="politicaSim">
                                  Sim
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="politica" id="politicaNao" value="nao" required>
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
                                <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasAlto" value="alto" required>
                                <label class="form-check-label" for="ferramentasAlto">
                                  Alto
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasMedio" value="medio" required>
                                <label class="form-check-label" for="ferramentasMedio">
                                  Médio
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ferramentas" id="ferramentasBaixo" value="baixo" required>
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
                                <input class="form-check-input" type="radio" name="observacoes" id="obsSim" value="sim" required>
                                <label class="form-check-label" for="obsSim">
                                  Sim
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="observacoes" id="obsNao" value="nao" required>
                                <label class="form-check-label" for="obsNao">
                                  Não
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6 offset-md-3">

                          <div class="col-auto">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">* Valor Hora R$</div>
                              </div>
                              <input type="text" class="form-control" id="valorHora" name="valorHora" min="0" maxlength="8" required>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="offset-md-2"><span>*Campos obrigatórios</span></div>

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="btnNewOrder" name="btnNewOrder">Gerar Orçamento</button>
                      </div>

                    </form>
                    <!-- /. form -->
                  <?php
                  else :
                    echo "Não existem clientes cadastrados";
                  endif;
                  ?>

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
  <script src="../../plugins/select2/select2.min.js"></script>
  <script src="../../plugins/jquery/jquery.mask.min.js"></script>
  <script src="js/validatorValorHora.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      // Limpar seleção do select2
      $('#cliente').val(null).trigger('change');
      $('#cliente').select2();
    });
  </script>

<?php endif; ?>