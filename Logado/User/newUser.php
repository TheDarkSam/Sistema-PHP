<?php
session_start();

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$_SESSION['acesso']['usuario']['incluir']) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuUser.php';
  include_once 'includes/listServices.php';
  global $servicos;
?>

  <script>
    document.title = 'Cadastro de usuário';
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
              <i class="fas fa-users mr-1"></i>Novo usuário
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actNewUser" method="POST" id="formCadastro">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome">* Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="login">* Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="senha1">* Senha</label>
                        <input type="password" class="form-control" id="senha1" name="senha1" minlength="5" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="senha2">* Confirmar senha</label>
                        <input type="password" class="form-control" id="senha2" name="senha2" minlength="5" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-lg-12">
                        <div class="form-group col-lg-12"></div>
                        <h4 class="text-center font-weight-bold">*Nível de acesso</h4>
                        <div class="table-responsive">
                          <table class="table text-center table-bordered">
                            <thead class="thead-dark">
                              <tr>
                                <th scope="col">Itens</th>
                                <th scope="col">Visualizar</th>
                                <th scope="col">Incluir</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Excluir</th>
                              </tr>
                            </thead>
                            <tbody id="corpoForm">
                              <?php foreach ($servicos as $linha => $valor) :
                                echo
                                  "<tr>
                              <th scrpe='row'>{$valor}</th>
                              
                              <td>
                                <div class='custom-control custom-switch custom-switch-on-olive'>
                                  <input type='checkbox' class='custom-control-input' id='visualizar_{$linha}' name='visualizar_{$linha}' onclick='limparChecked(`{$linha}`)'>
                                  <label class='custom-control-label' for='visualizar_{$linha}'></label>
                                </div>
                              </td>

                              <td>
                                <div class='custom-control custom-switch custom-switch-on-olive'>
                                  <input type='checkbox' class='custom-control-input' id='incluir_{$linha}' name='incluir_{$linha}'>
                                  <label class='custom-control-label' for='incluir_{$linha}'></label>
                                </div>
                              </td>

                              <td>
                                <div class='custom-control custom-switch custom-switch-on-olive'>
                                  <input type='checkbox' class='custom-control-input' id='editar_{$linha}' name='editar_{$linha}' onclick='checkedVisualizar(visualizar_{$linha}, editar_{$linha})'>
                                  <label class='custom-control-label' for='editar_{$linha}'></label>
                                </div>
                              </td>

                              <td>
                                <div class='custom-control custom-switch custom-switch-on-olive'>
                                  <input type='checkbox' class='custom-control-input' id='excluir_{$linha}' name='excluir_{$linha}' onclick='checkedVisualizar(visualizar_{$linha}, excluir_{$linha})'>
                                  <label class='custom-control-label' for='excluir_{$linha}'></label>
                                </div>
                              </td>
                            </tr>
                            ";
                              endforeach;
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    * Campos obrigatórios

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" id="btnNewUser" name="btnNewUser">Cadastrar</button>
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

  <script src="js/configNewUser.js"></script>
  <script>
    document.getElementById('btnNewUser').onclick = function() {
      if (validator() == true) {
        <?php $_SESSION['listaServicos'] = $servicos; ?>
      }
    }
  </script>

<?php endif; ?>