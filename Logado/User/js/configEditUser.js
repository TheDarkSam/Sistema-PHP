let btnEditarSenha = document.getElementById('checkboxEditar');

btnEditarSenha.onclick = function () {
  let senha1 = document.getElementById('senha1');
  let senha2 = document.getElementById('senha2');

  if (btnEditarSenha.checked) {
    senha1.readOnly = false;
    senha2.readOnly = false;
    senha1.value = '';
    senha2.value = '';
  } else {
    senha1.readOnly = true;
    senha2.readOnly = true;
    senha1.value = '***********';
    senha2.value = '***********';
  }
}