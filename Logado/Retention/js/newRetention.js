let destino = document.getElementById('destino');
let prazo = document.getElementById('prazo');

destino.onchange = function () {
  if (destino.value == "Indefinido" || destino.value == "Permanente") {
    prazo.value = destino.value;
    prazo.readOnly = true;
  } else {
    prazo.value = "";
    prazo.readOnly = false;
  }
}

arquivo.onchange = function() {
  let pos = arquivo.value.lastIndexOf(".");
  let tam = arquivo.value.lenght;
  let extensao = arquivo.value.substring(pos, tam);

  let extensoesValidas = [".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf"];

  if (extensao != "" && extensoesValidas.indexOf(extensao) < 0) {
    alert("Arquivo selecionado é inválido!");
    arquivo.value = null;
  }
}