let radioPercentual = document.getElementById('radioPercentual');
let radioDinheiro = document.getElementById('radioDinheiro');
let radioSemEntrada = document.getElementById('radioSemEntrada');
let radioAvista = document.getElementById('radioAvista');

let divEntrada = document.getElementById('divEntrada');
let divParcelamento = document.getElementById('divParcelamento');
let divMsg = document.getElementById('divMsg');

let formValorTotal = document.getElementById('formValorTotal');

function formatarDinheiro(input) {
  return input.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

radioPercentual.onclick = function () {
  divMsg.innerHTML = ``;
  let valorTotal = parseFloat(formValorTotal.value.replace('R$', '').replace('.', '').replace(' ', '').replace(',', '.'));
  divMsg.innerHTML = '';
  divParcelamento.innerHTML = '';

  divEntrada.innerHTML = '';

  let aux = [];
  aux.push(5 * valorTotal / 100);
  aux.push(10 * valorTotal / 100);
  aux.push(15 * valorTotal / 100);
  aux.push(20 * valorTotal / 100);
  aux.push(25 * valorTotal / 100);
  aux.push(30 * valorTotal / 100);
  aux.push(35 * valorTotal / 100);
  aux.push(40 * valorTotal / 100);
  aux.push(45 * valorTotal / 100);
  aux.push(50 * valorTotal / 100);
  aux.push(55 * valorTotal / 100);
  aux.push(60 * valorTotal / 100);
  aux.push(65 * valorTotal / 100);
  aux.push(70 * valorTotal / 100);
  aux.push(75 * valorTotal / 100);
  aux.push(80 * valorTotal / 100);
  aux.push(85 * valorTotal / 100);
  aux.push(90 * valorTotal / 100);
  aux.push(95 * valorTotal / 100);

  divEntrada.innerHTML = `<div class="input-group-prepend">
                            <div class="input-group-text">Entrada</div>
                          </div>
                          <select id="formEntrada" class="form-control" name="valorEntrada" required>
                          <option></option>
                            <option value='${aux[0]}'> 5%: ${formatarDinheiro(aux[0])}</option>
                            <option value='${aux[1]}'>10%: ${formatarDinheiro(aux[1])}</option>
                            <option value='${aux[2]}'>15%: ${formatarDinheiro(aux[2])}</option>
                            <option value='${aux[3]}'>20%: ${formatarDinheiro(aux[3])}</option>
                            <option value='${aux[4]}'>25%: ${formatarDinheiro(aux[4])}</option>
                            <option value='${aux[5]}'>30%: ${formatarDinheiro(aux[5])}</option>
                            <option value='${aux[6]}'>35%: ${formatarDinheiro(aux[6])}</option>
                            <option value='${aux[7]}'>40%: ${formatarDinheiro(aux[7])}</option>
                            <option value='${aux[8]}'>45%: ${formatarDinheiro(aux[8])}</option>
                            <option value='${aux[9]}'>50%: ${formatarDinheiro(aux[9])}</option>
                            <option value='${aux[10]}'>55%: ${formatarDinheiro(aux[10])}</option>
                            <option value='${aux[11]}'>60%: ${formatarDinheiro(aux[11])}</option>
                            <option value='${aux[12]}'>65%: ${formatarDinheiro(aux[12])}</option>
                            <option value='${aux[13]}'>70%: ${formatarDinheiro(aux[13])}</option>
                            <option value='${aux[14]}'>75%: ${formatarDinheiro(aux[14])}</option>
                            <option value='${aux[15]}'>80%: ${formatarDinheiro(aux[15])}</option>
                            <option value='${aux[16]}'>85%: ${formatarDinheiro(aux[16])}</option>
                            <option value='${aux[17]}'>90%: ${formatarDinheiro(aux[17])}</option>
                            <option value='${aux[18]}'>95%: ${formatarDinheiro(aux[18])}</option>
                          </select>`;

  let formEntrada = document.getElementById('formEntrada');

  formEntrada.onchange = function () {
    formEntrada = document.getElementById('formEntrada');
    divMsg.innerHTML = ``;
    //divEntrada.innerHTML = ``;
    if (formEntrada.value == '') {
      divParcelamento.innerHTML = '';
    } else {
      let auxParcelamento = [];
      for (i = 1; i <= 12; i++) {
        auxParcelamento.push((valorTotal - formEntrada.value) / i);
      }
      divParcelamento.innerHTML = `<div class="input-group-prepend">
                                    <div class="input-group-text">Parcelas</div>
                                  </div>
                                  <select id="formParcelamento" class="form-control" name="totParcelas" required>
                                    <option></option>
                                    <option value='1'>1 de ${formatarDinheiro(auxParcelamento[0])}</option>
                                    <option value='2'>2 de ${formatarDinheiro(auxParcelamento[1])}</option>
                                    <option value='3'>3 de ${formatarDinheiro(auxParcelamento[2])}</option>
                                    <option value='4'>4 de ${formatarDinheiro(auxParcelamento[3])}</option>
                                    <option value='5'>5 de ${formatarDinheiro(auxParcelamento[4])}</option>
                                    <option value='6'>6 de ${formatarDinheiro(auxParcelamento[5])}</option>
                                    <option value='7'>7 de ${formatarDinheiro(auxParcelamento[6])}</option>
                                    <option value='8'>8 de ${formatarDinheiro(auxParcelamento[7])}</option>
                                    <option value='9'>9 de ${formatarDinheiro(auxParcelamento[8])}</option>
                                    <option value='10'>10 de ${formatarDinheiro(auxParcelamento[9])}</option>
                                    <option value='11'>11 de ${formatarDinheiro(auxParcelamento[10])}</option>
                                    <option value='12'>12 de ${formatarDinheiro(auxParcelamento[11])}</option>
                                  </select>`;
    }

    let formParcelamento = document.getElementById('formParcelamento');

    if (formEntrada.value != '') {
      formParcelamento.onchange = function () {
        let valorEntrada = parseFloat(formEntrada.value);
        let valorParcela = (valorTotal - formEntrada.value) / (formParcelamento.value);
        if (formParcelamento.value != '') {
          divMsg.innerHTML = `<div class='bg-info text-center alert'>Entrada de ${formatarDinheiro(valorEntrada)} e ${formParcelamento.value} parcelas de ${formatarDinheiro(valorParcela)} </div>`;
        } else {
          divMsg.innerHTML = ``;
        }
      }
    } else {
      divMsg.innerHTML = ``;
    }
  }
}

radioDinheiro.onclick = function () {
  let valorTotal = parseFloat(formValorTotal.value.replace('R$', '').replace('.', '').replace(' ', '').replace(',', '.'));

  divEntrada.innerHTML = '';
  divParcelamento.innerHTML = '';
  divMsg.innerHTML = '';

  divEntrada.innerHTML = `<div class="input-group-prepend">
                            <div class="input-group-text">Entrada</div>
                          </div>
                          <input type='number' min='0' max='${valorTotal}' step='0.01' class='form-control' id='formEntrada' name='valorEntrada'>`;

  let formEntrada = document.getElementById('formEntrada');

  formEntrada.onkeyup = function () {
    divMsg.innerHTML = ``;
    if (formEntrada.value != '') {
      if (formEntrada.value > valorTotal || formEntrada.value < 0) {
        alert('Valor invalido');

      } else if (formEntrada.value == valorTotal) {
        divMsg.innerHTML = `<div class='bg-info text-center alert'>Pagamento avista</div>`;
        divParcelamento.innerHTML = '';
      } else {
        let auxParcelamento = [];
        for (i = 1; i <= 12; i++) {
          auxParcelamento.push((valorTotal - formEntrada.value) / i);
        }

        let parcelamentoAux = `<div class="input-group-prepend">
                                    <div class="input-group-text">Parcelas</div>
                                  </div>
                                  <select id="formParcelamento" class="form-control" name="totParcelas" required>
                                    <option></option>`;
        for (let i = 0; i < 12; i++) {
          if (parseFloat(auxParcelamento[i]).toFixed(2) > 0) {
            parcelamentoAux += `<option value='${i + 1}'>${i + 1} de ${formatarDinheiro(auxParcelamento[i])}</option>`;
          }
        }


        parcelamentoAux += `</select>`;

        divParcelamento.innerHTML = parcelamentoAux;

        let formParcelamento = document.getElementById('formParcelamento');

        formParcelamento.onchange = function () {
          let valorEntrada = parseFloat(formEntrada.value);


          if (formParcelamento.value != '') {
            if (valorEntrada > 0 && valorEntrada <= valorTotal) {
              let valorParcelamento = (valorTotal - valorEntrada) / formParcelamento.value;
              divMsg.innerHTML = `<div class='bg-info text-center alert'>Entrada de ${formatarDinheiro(valorEntrada)} e ${formParcelamento.value} de ${formatarDinheiro(valorParcelamento)}</div>`;
            }

          } else {
            divMsg.innerHTML = ``;
          }

        }
      }
    }
  }
}

radioSemEntrada.onclick = function () {
  divMsg.innerHTML = '';
  let valorTotal = parseFloat(formValorTotal.value.replace('R$', '').replace('.', '').replace(' ', '').replace(',', '.'));

  divEntrada.innerHTML = '';
  let auxParcelamento = [];
  for (i = 1; i <= 12; i++) {
    auxParcelamento.push((valorTotal) / i);
  }
  divParcelamento.innerHTML = `<div class="input-group-prepend">
                                <div class="input-group-text">Parcelas</div>
                              </div>
                              <select id="formParcelamento" class="form-control" name="totParcelas" required>
                                <option></option>
                                <option value='1'>1 de ${formatarDinheiro(auxParcelamento[0])}</option>
                                <option value='2'>2 de ${formatarDinheiro(auxParcelamento[1])}</option>
                                <option value='3'>3 de ${formatarDinheiro(auxParcelamento[2])}</option>
                                <option value='4'>4 de ${formatarDinheiro(auxParcelamento[3])}</option>
                                <option value='5'>5 de ${formatarDinheiro(auxParcelamento[4])}</option>
                                <option value='6'>6 de ${formatarDinheiro(auxParcelamento[5])}</option>
                                <option value='7'>7 de ${formatarDinheiro(auxParcelamento[6])}</option>
                                <option value='8'>8 de ${formatarDinheiro(auxParcelamento[7])}</option>
                                <option value='9'>9 de ${formatarDinheiro(auxParcelamento[8])}</option>
                                <option value='10'>10 de ${formatarDinheiro(auxParcelamento[9])}</option>
                                <option value='11'>11 de ${formatarDinheiro(auxParcelamento[10])}</option>
                                <option value='12'>12 de ${formatarDinheiro(auxParcelamento[11])}</option>
                              </select>`;

  formParcelamento.onchange = function () {
    let valorEntrada = valorTotal / formParcelamento.value;
    if (formParcelamento.value != '') {
      divMsg.innerHTML = `<div class='bg-info text-center alert'>${formParcelamento.value} parcelas de ${formatarDinheiro(valorEntrada)}</div>`;
    }
  }
}

radioAvista.onclick = function () {
  divEntrada.innerHTML = '';
  divParcelamento.innerHTML = '';
  divMsg.innerHTML = `<div class='bg-info text-center alert'>Pagamento À vista</div>`;
}