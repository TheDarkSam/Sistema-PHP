<?php
// Conexão
require_once '../../conexao/sistemaConnect.php';
require_once '../operations/Formats.php';

// Sessão
session_start();

$formats = new Formats;

$existe = false;

$situacaoPagamento = "";
if (isset($_GET['id'])) {
  $sql = "SELECT o.*, c.*, l.*, p.valorEntrada, p.totParcelas, p.totalOrcamento FROM orcamento o INNER JOIN cliente c ON o.idCliente = c.idCliente INNER JOIN lgpd l ON o.idOrcamento = l.idOrcamento INNER JOIN pagamento p ON p.idOrcamento = o.idOrcamento AND o.idOrcamento = '{$_GET['id']}'";
  $resultado = mysqli_query($connect, $sql);
  if (mysqli_num_rows($resultado) == 1) {
    $dados = mysqli_fetch_array($resultado);
    $valorMinuto = round($dados['valorHora'] / 60, 2);
    $existe = true;

    if ($dados['valorEntrada'] == 0 && $dados['totParcelas'] == 0) {
      $situacaoPagamento = "Pagamento não definido";
    } elseif ($dados['valorEntrada'] == 0) {
      $valorParcela = round($dados['totalOrcamento'] / $dados['totParcelas'], 2);
      $situacaoPagamento = "Sem entrada, parcelado em {$dados['totParcelas']} de {$formats->formatarDinheiro($valorParcela)}";
    } elseif ($dados['totParcelas'] == 0) {
      $situacaoPagamento = "Pagamento à vista de {$formats->formatarDinheiro($dados['totalOrcamento'])}";
    } else {
      $valorParcela = round(($dados['totalOrcamento'] - $dados['valorEntrada']) / $dados['totParcelas'], 2);
      $situacaoPagamento = "Entrada {$formats->formatarDinheiro($dados['valorEntrada'])} e {$dados['totParcelas']} parcelas de {$formats->formatarDinheiro($valorParcela)}";
    }

    $arrayFormulario = array();
    $arrayFormulario['tipoDados'] = $formats->formatarOrcamento($dados['tipoDados']);
    $arrayFormulario['ti'] = $formats->formatarOrcamento($dados['ti']);
    $arrayFormulario['juridico'] = $formats->formatarOrcamento($dados['juridico']);
    $arrayFormulario['politica'] = $formats->formatarOrcamento($dados['politica']);
    $arrayFormulario['ferramentas'] = $formats->formatarOrcamento($dados['ferramentas']);
    $arrayFormulario['observacoes'] = $formats->formatarOrcamento($dados['observacoes']);
    $arrayFormulario['valorHora'] = $formats->formatarDinheiro($dados['valorHora']);

    $arrayOrcamento = array();
    $arrayOrcamento['coleta'] = array('tempo' => $formats->formatarHoraMinutos($dados['coleta']), 'valor' => $formats->formatarDinheiro($dados['coleta'] * $valorMinuto));

    $arrayOrcamento['gap'] = array('tempo' => $formats->formatarHoraMinutos($dados['gap']), 'valor' => $formats->formatarDinheiro($dados['gap'] * $valorMinuto));

    $arrayOrcamento['planoAcao'] = array('tempo' => $formats->formatarHoraMinutos($dados['planoAcao']), 'valor' => $formats->formatarDinheiro($dados['planoAcao'] * $valorMinuto));

    $arrayOrcamento['implantacao'] = array('tempo' => $formats->formatarHoraMinutos($dados['implantacao']), 'valor' => $formats->formatarDinheiro($dados['implantacao'] * $valorMinuto));

    $arrayOrcamento['relatorio'] = array('tempo' => $formats->formatarHoraMinutos($dados['relatorio']), 'valor' => $formats->formatarDinheiro($dados['relatorio'] * $valorMinuto));

    $arrayOrcamento['treinamento'] = array('tempo' => $formats->formatarHoraMinutos($dados['treinamento']), 'valor' => $formats->formatarDinheiro($dados['treinamento'] * $valorMinuto));

    $arrayOrcamento['dpo'] = array('tempo' => $formats->formatarHoraMinutos($dados['dpo']), 'valor' => $formats->formatarDinheiro($dados['dpo'] * $valorMinuto));

    $arrayOrcamento['validacao'] = array('tempo' => $formats->formatarHoraMinutos($dados['validacao']), 'valor' => $formats->formatarDinheiro($dados['validacao'] * $valorMinuto));

    $arrayOrcamento['melhorias'] = array('tempo' => $formats->formatarHoraMinutos($dados['melhorias']), 'valor' => $formats->formatarDinheiro($dados['melhorias'] * $valorMinuto));

    $arrayOrcamento['total'] = array('tempo' => $formats->formatarHoraMinutos($dados['total']), 'valor' => $formats->formatarDinheiro($dados['total'] * $valorMinuto));
  }
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || (!$_SESSION['acesso']['orcamento']['visualizar'] && !$_SESSION['acesso']['orcamento']['incluir']) || !$existe) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuOrder.php';
?>

  <script>
    document.title = "Visualizar Orçamento";
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
              <i class="fas fa-file-alt mr-1"></i>Orçamento
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-12">

                  <?php
                  if ($existe) : ?>
                    <div>
                      <div class="row">
                        <div class="col-12">
                          <h6 class="font-weight-bolder">Código Orçamento: <?php echo $dados['idOrcamento']; ?></h6>
                          <h6 class="font-weight-bolder">Cliente: <?php echo $dados['nomeEmpresa']; ?> </h6>
                          <h6 class="font-weight-bolder">Responsável: <?php echo $dados['nomeResponsavel']; ?> </h6>
                          <h6 class="font-weight-bolder">Situação: <?php echo $dados['situacao']; ?></h6>
                          <h6 class="font-weight-bolder">Pagamento: <?php echo $situacaoPagamento . "."; ?></h6>
                          <h6 class="font-weight-bolder">Valor Total: <?php echo $arrayOrcamento['total']['valor']; ?></h6>
                        </div>
                      </div>
                    </div>

                    <!-- Menu opções -->
                    <div class="dropdown text-right">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ações
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <?php
                        if ($_SESSION['acesso']['orcamento']['editar'] && $dados['situacao'] == 'Pendente') : ?>
                          <!-- Item -->
                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalPagamento">
                            <span class="fa fa-pen text-primary"></span> Definir pagamento
                          </a>
                          <!-- /. Item -->

                          <!-- Item -->
                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalValorHora">
                            <span class="fa fa-edit text-primary"></span> Editar Valor da Hora
                          </a>
                          <!-- /. Item -->

                          <form action="editOrder" method="POST">
                            <input type="hidden" name="idOrcamento" value="<?php echo $_GET['id']; ?>">
                            <!-- Item -->
                            <button class="dropdown-item" type="submit" name='btnUpdate'>
                              <span class="fa fa-edit text-primary"></span> Editar formulário
                            </button>
                            <!-- /. Item -->
                          </form>

                          <form action="editLgpd" method="POST">
                            <input type="hidden" name="idOrcamento" value="<?php echo $_GET['id']; ?>">
                            <!-- Item -->
                            <button class="dropdown-item" type="submit" name='btnUpdate'>
                              <span class="fa fa-edit text-primary"></span> Editar Orçamento
                            </button>
                            <!-- /. Item -->
                          </form>


                          <form action="php_action/actOrderState" method="POST">
                            <input type="hidden" name="id" value="<?php echo $dados['idOrcamento']; ?>">

                            <?php
                            if ($situacaoPagamento != "Pagamento não definido") : ?>
                              <!-- Item -->
                              <button class="dropdown-item" type="submit" name="btnAprovar">
                                <span class="fa fa-check-circle text-success"></span> Aprovar Orçamento
                              </button>
                              <!-- /. Item -->
                            <?php
                            endif; ?>

                            <!-- Item -->
                            <button class="dropdown-item" type="submit" name="btnReprovar">
                              <span class="fa fa-times-circle text-danger"></span> Reprovar Orçamento
                            </button>
                            <!-- /. Item -->
                          </form>

                        <?php
                        endif; ?>

                        <form action="pdf/viewPdf" target="_blank" method="POST">
                          <input type="hidden" name="idOrcamento" value="<?php echo $dados['idOrcamento']; ?>">
                          <!-- Item -->
                          <button class="dropdown-item" type="submit" name="btnPdf">
                            <span class="fa fa-file-pdf text-danger"></span> Gerar PDF
                          </button>
                          <!-- /. Item -->
                        </form>
                      </div>
                    </div>
                    <!-- /. Menu opções -->

                    <hr>

                    <div class="row">
                      <div class="col-lg-5">
                        <p class="text-center"><strong>Dados Formulário</strong></p>
                        <div class="table-responsive">
                          <table id="tableMinutos" class="table table-striped table-bordered table-hover text-center">
                            <thead class="thead-dark">
                              <tr>
                                <th>Item</th>
                                <th>Dado</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Departamentos</td>
                                <td><?php echo $dados['departamentos']; ?></td>
                              </tr>
                              <tr>
                                <td>Compartilhamentos</td>
                                <td><?php echo $dados['compartilhamentos']; ?></td>
                              </tr>
                              <tr>
                                <td>Funcionários internos</td>
                                <td><?php echo $dados['funcInternos']; ?></td>
                              </tr>
                              <tr>
                                <td>Funcionários externos</td>
                                <td><?php echo $dados['funcExternos']; ?></td>
                              </tr>
                              <tr>
                                <td>Dispositivos</td>
                                <td><?php echo $dados['dispositivos']; ?></td>
                              </tr>
                              <tr>
                                <td>Sistemas</td>
                                <td><?php echo $dados['sistemas']; ?></td>
                              </tr>
                              <tr>
                                <td>Tipo de dados</td>
                                <td><?php echo $arrayFormulario['tipoDados']; ?></td>
                              </tr>
                              <tr>
                                <td>T.I.</td>
                                <td><?php echo $arrayFormulario['ti']; ?></td>
                              </tr>
                              <tr>
                                <td>Jurídico</td>
                                <td><?php echo $arrayFormulario['juridico']; ?></td>
                              </tr>
                              <tr>
                                <td>Política de segurança</td>
                                <td><?php echo $arrayFormulario['politica']; ?></td>
                              </tr>
                              <tr>
                                <td>Ferramentas</td>
                                <td><?php echo $arrayFormulario['ferramentas']; ?></td>
                              </tr>
                              <tr>
                                <td>Observações</td>
                                <td><?php echo $arrayFormulario['observacoes']; ?></td>
                              </tr>
                              <tr>
                                <td>Valor hora</td>
                                <td><?php echo $arrayFormulario['valorHora']; ?></td>
                              </tr>
                            </tbody>

                          </table>
                        </div>
                      </div>

                      <div class="col-lg-7">
                        <p class="text-center"><strong>Dados Orçamento</strong></p>
                        <div class="table-responsive">
                          <table id="tableMinutos" class="table table-striped table-bordered table-hover text-center">
                            <thead class="thead-dark">
                              <tr>
                                <th>Item</th>
                                <th>Tempo</th>
                                <th>Valor</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Coleta</td>
                                <td><?php echo $arrayOrcamento['coleta']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['coleta']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Gap Analysis</td>
                                <td><?php echo $arrayOrcamento['gap']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['gap']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Plano de ação</td>
                                <td><?php echo $arrayOrcamento['planoAcao']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['planoAcao']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Implantação</td>
                                <td><?php echo $arrayOrcamento['implantacao']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['implantacao']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Relatório de impacto</td>
                                <td><?php echo $arrayOrcamento['relatorio']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['relatorio']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Treinamento</td>
                                <td><?php echo $arrayOrcamento['treinamento']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['treinamento']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>DPO</td>
                                <td><?php echo $arrayOrcamento['dpo']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['dpo']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Validação</td>
                                <td><?php echo $arrayOrcamento['validacao']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['validacao']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Melhorias</td>
                                <td><?php echo $arrayOrcamento['melhorias']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['melhorias']['valor']; ?></td>
                              </tr>
                              <tr>
                                <td>Total</td>
                                <td><?php echo $arrayOrcamento['total']['tempo']; ?></td>
                                <td><?php echo $arrayOrcamento['total']['valor']; ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <hr>



                  <?php endif; ?>

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

  <?php
  if ($_SESSION['acesso']['orcamento']['editar'] && $dados['situacao'] == 'Pendente') : ?>
    <!-- Modal Pagamento -->
    <div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-labelledby="modalPagamentoLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPagamentoLabel">Pagamento</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="php_action/actValidatePayment" method="POST">
              <div class="col-auto">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Valor total</div>
                  </div>
                  <input type="text" class="form-control" id="formValorTotal" class="bg-info" name="totalOrcamento" readonly value="<?php echo $formats->formatarDinheiro($valorMinuto * $dados['total']) ?>">
                </div>
              </div>

              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label pt-0 text-center">
                    <h3>Entrada</h3>
                  </legend>
                  <div class="col-sm-12 text-center">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="radioTipoEntrada" id="radioPercentual" value="percentual" required>
                      <label class="form-check-label" for="radioPercentual">
                        Percentual
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="radioTipoEntrada" id="radioDinheiro" value="dinheiro" required>
                      <label class="form-check-label" for="radioDinheiro">
                        Dinheiro
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="radioTipoEntrada" id="radioSemEntrada" value="semEntrada" required>
                      <label class="form-check-label" for="radioSemEntrada">
                        Sem entrada
                      </label>
                    </div>

                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="radioTipoEntrada" id="radioAvista" value="avista" required>
                      <label class="form-check-label" for="radioAvista">
                        À vista
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>

              <div class="col-auto">
                <div class="input-group" id="divEntrada">
                </div>
              </div>

              <hr>

              <div class="col-auto">
                <div class="input-group" id="divParcelamento">
                </div>
              </div>

              <div class="mb-2"></div>

              <div id="divMsg" class="mb-2">

              </div>
              <div class="text-center">
                <input type="hidden" name="id" value="<?php echo $dados['idOrcamento']; ?>">
                <button type="submit" class="btn btn-primary mb-2" name="btnValidate">Confirmar</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
    </div>
    <!-- ./ Modal Pagamento -->

    <!-- Modal Valor Hora -->
    <div class="modal fade" id="modalValorHora" tabindex="-1" role="dialog" aria-labelledby="modalValorHoraLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalValorHoraLabel">Valor da hora</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="php_action/actAlterHour" method="POST" id="formNovoValorHora" onsubmit="return validator()">
              <div class="col-auto">
                <h5 class="text-center font-weigth-bolder">Valor da hora atual: <?php echo $arrayFormulario['valorHora']; ?></h5>
              </div>

              <hr>

              <div class="col-auto">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Novo valor da hora R$</div>
                  </div>
                  <input type="text" class="form-control" id="valorHora" name="valorHora" min="2" maxlength="8" required>
                </div>
              </div>

              <div class="text-center">
                <input type="hidden" name="id" value="<?php echo $dados['idOrcamento']; ?>">
                <button type="submit" class="btn btn-primary mb-2" id="btnAlterHour" name="btnAlterHour">Confirmar</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
    </div>
    <!-- ./ Modal Valor Hora -->
  <?php endif; ?>

  <?php include_once '../includes/footer.php'; ?>
  <script src="js/validatorPayment.js"></script>  
  <script src="../../plugins/jquery/jquery.mask.min.js"></script>
  <script src="js/validatorValorHora.js"></script>

<?php endif; ?>