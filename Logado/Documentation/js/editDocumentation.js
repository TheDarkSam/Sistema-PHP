let btnEditarAnexo = document.getElementById('checkboxEditar');

var arquivo = document.getElementById('arquivo');
let arquivoAtual = document.getElementById('arquivoAtual');
arquivo.hidden = true;
arquivoAtual.hidden = false;

btnEditarAnexo.onclick = function () {
  if (btnEditarAnexo.checked) {
    arquivoAtual.hidden = true;
    arquivo.hidden = false;
    arquivo.required = true;
  } else {
    arquivoAtual.hidden = false;
    arquivo.hidden = true;
    arquivo.required = false;
  }
}