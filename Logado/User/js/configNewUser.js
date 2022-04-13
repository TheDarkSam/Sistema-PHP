function validator() {
  let nome = document.getElementById('nome').value;
  let login = document.getElementById('login').value;
  let senha1 = document.getElementById('senha1').value;
  let senha2 = document.getElementById('senha2').value;

  if (nome != '' && login != '' && senha1 == senha2) {
    return true;
  } else {
    return false;
  }
}


document.getElementById('formCadastro').onsubmit = function () {
  let corpoForm = document.getElementById('corpoForm');
  
  let senha1 = document.getElementById('senha1').value;
  let senha2 = document.getElementById('senha2').value;

  let verificar = true;

  if (corpoForm) {
    verificar = false;
    let inputs = corpoForm.getElementsByTagName('input');
    for (let i = 0; i < inputs.length; i++) {
      if (inputs[i].checked) {
        verificar = true;
        break;
      }
    }
  }

  if (validator() && verificar) {
    return true;
  } else if (senha1 != senha2) {
    alert('Senha não conferem!');
    return false;
  } else {
    alert('Preencha ao menos um nível de acesso');
    return false;
  }
}

function checkedVisualizar(visualizar, input) {
  if (input.checked == true) {
    visualizar.checked = true;
  }
}

function limparChecked(input) {
  let visualizar = document.getElementById('visualizar_' + input);
  if (visualizar.checked == false) {
    document.getElementById('editar_' + input).checked = false;
    document.getElementById('excluir_' + input).checked = false;
  }
}

