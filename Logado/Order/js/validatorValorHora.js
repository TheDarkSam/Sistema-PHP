$("#valorHora").mask('#.##0,00', {
  reverse: true
});

function validator() {
  let inputHora = document.getElementById('valorHora');
  let hora = inputHora.value.replace('.', '');
  hora = hora.replace(',', '.');
  hora = parseFloat(hora);

  if (hora < 10) {
    inputHora.focus();
    alert('Valor da hora muito baixo');
    return false;
  } else {
    return true;
  }
}