<?php
session_start();

// Conexão
require_once '../../conexao/usuarioConnect.php';

function clear($input)
{
  global $connect;
  $var = mysqli_escape_string($connect, $input);
  $var = htmlspecialchars($var);
  return $var;
}

$existe = false;

if (isset($_SESSION['id_usuario'])) {
  $id = clear($_SESSION['id_usuario']);
  $sql = "SELECT * FROM usuario WHERE idUsuario = '{$id}'";
  $resultado = mysqli_query($connect, $sql);

  if (mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_array($resultado);
    $sqlAcesso = "SELECT * FROM controleAcesso WHERE idUsuario = '{$id}'";
    $rAcesso = mysqli_query($connect, $sqlAcesso);
    if (mysqli_num_rows($resultado) > 0) {
      $arrayAcesso = array();
      while ($dadosAcesso = mysqli_fetch_array($rAcesso)) {
        array_push($arrayAcesso, $dadosAcesso);
      }

      $existe = true;
    }
  }
}

mysqli_close($connect);

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['usuario']) || !$existe) :
  header('Location: ../../index');
else :
  include_once '../includes/header.php';
  include_once 'includes/menuUser.php';
  include_once 'includes/listServices.php';
  global $servicos;
?>

  <script>
    document.title = 'Meu perfil';
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
              <i class="fas fa-users mr-1"></i>Meu perfil
            </div>
            <!-- ./ card-header -->
            <!-- card-body -->
            <div class="card-body">
              <!-- row -->
              <div class="row">
                <!-- coluna -->
                <div class="col-lg-10 offset-lg-1">
                  <!-- form -->
                  <form role="form" action="php_action/actEditMyPerfil" method="POST" id="formCadastro">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="nome">* Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $dados['nome']; ?>" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="login">* Login</label>
                        <input type="text" class="form-control" id="login" name="login" value="<?php echo $dados['login']; ?>" required readonly>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="senha1">* Senha</label>
                        <input type="password" class="form-control" id="senha1" name="senha1" minlength="5" value="***********" required readonly>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="senha2">* Confirmar senha</label>
                        <input type="password" class="form-control" id="senha2" name="senha2" minlength="5" value="***********" required readonly>
                      </div>
                    </div>

                    <div class='custom-control custom-switch custom-switch-on-olive'>
                      <input type='checkbox' class='custom-control-input' id="checkboxEditar" name="checkboxEditar">
                      <label class='custom-control-label' for='checkboxEditar'>Editar senha</label>
                    </div>

                    <?php if ($_SESSION['acesso']['usuario']['editar']) : ?>
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
                                <?php foreach ($arrayAcesso as $acesso) :
                                  if (isset($servicos[$acesso['pagina']])) :
                                    $visualizar = $acesso['visualizar'] == 1 ? 'checked' : ' ';
                                    $incluir = $acesso['incluir'] == 1 ? 'checked' : ' ';
                                    $editar = $acesso['editar'] == 1 ? 'checked' : ' ';
                                    $excluir = $acesso['excluir'] == 1 ? 'checked' : ' ';
                                    echo
                                      "<tr>
                                        <th>{$servicos[$acesso['pagina']]}</th>
                                    
                                        <td>
                                          <div class='custom-control custom-switch custom-switch-on-olive'>
                                            <input type='checkbox' class='custom-control-input' id='visualizar_{$acesso['pagina']}' name='visualizar_{$acesso['pagina']}' onclick='limparChecked(`{$acesso['pagina']}`)' {$visualizar}>
                                            <label class='custom-control-label' for='visualizar_{$acesso['pagina']}'></label>
                                          </div>
                                        </td>

                                        <td>
                                          <div class='custom-control custom-switch custom-switch-on-olive'>
                                            <input type='checkbox' class='custom-control-input' id='incluir_{$acesso['pagina']}' name='incluir_{$acesso['pagina']}' {$incluir}>
                                            <label class='custom-control-label' for='incluir_{$acesso['pagina']}'></label>
                                          </div>
                                        </td>

                                        <td>
                                          <div class='custom-control custom-switch custom-switch-on-olive'>
                                            <input type='checkbox' class='custom-control-input' id='editar_{$acesso['pagina']}' name='editar_{$acesso['pagina']}' onclick='checkedVisualizar(visualizar_{$acesso['pagina']}, editar_{$acesso['pagina']})' {$editar}>
                                            <label class='custom-control-label' for='editar_{$acesso['pagina']}'></label>
                                          </div>
                                        </td>

                                        <td>
                                          <div class='custom-control custom-switch custom-switch-on-olive'>
                                            <input type='checkbox' class='custom-control-input' id='excluir_{$acesso['pagina']}' name='excluir_{$acesso['pagina']}' onclick='checkedVisualizar(visualizar_{$acesso['pagina']}, excluir_{$acesso['pagina']})' {$excluir}>
                                            <label class='custom-control-label' for='excluir_{$acesso['pagina']}'></label>
                                          </div>
                                        </td>
                                      </tr>";
                                  endif;
                                endforeach;
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>

                    * Campos obrigatórios

                    <div class="text-center">
                      <input type="hidden" value="<?php echo $dados['idUsuario']; ?>" name="id">
                      <button type="submit" class="btn btn-primary" id="btnEditUser" name="btnEditUser">Editar</button>
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
  <script src="js/configEditUser.js"></script>
  <script>
    document.getElementById('btnEditUser').onclick = function() {
      if (validator() == true) {
        <?php $_SESSION['listaServicos'] = $servicos; ?>
      }
    }
  </script>

<?php endif; ?>