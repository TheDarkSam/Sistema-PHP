valorMinuto = document.getElementById('valorMinuto').value;
let correto = false;

function modificarDinheiro(objValor, hora, min) {
  let soma = 0;
  correto = true;

  if (min.value > 59 || min.value == '') {
    min.focus();
    min.style.border = "2px dashed #FF0000";
    correto = false;
  }


  if (hora.value > 999 || hora.value == '') {
    hora.focus();
    hora.style.border = "2px dashed #FF0000";
    correto = false;
  }

  if (correto) {
    min.style.border = "1px solid #ced4da";
    hora.style.border = "1px solid #ced4da";
    soma = (parseInt(min.value) + parseInt(hora.value) * 60) * valorMinuto;
    objValor.innerText = soma.toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    });
  } else {
    objValor.innerText = 'R$ --';
  }

  atualizarTotal();
}

let valorColeta = document.getElementById('valorColeta');
let horaColeta = document.getElementById('horaColeta');
let minColeta = document.getElementById('minColeta');
horaColeta.onkeyup = minColeta.onkeyup = horaColeta.onchange = minColeta.onchange = function () {
  modificarDinheiro(valorColeta, horaColeta, minColeta);
}

let valorGap = document.getElementById('valorGap');
let horaGap = document.getElementById('horaGap');
let minGap = document.getElementById('minGap');
horaGap.onkeyup = minGap.onkeyup = horaGap.onchange = minGap.onchange = function () {
  modificarDinheiro(valorGap, horaGap, minGap);
}

let valorPlanoAcao = document.getElementById('valorPlanoAcao');
let horaPlanoAcao = document.getElementById('horaPlanoAcao');
let minPlanoAcao = document.getElementById('minPlanoAcao');
horaPlanoAcao.onkeyup = minPlanoAcao.onkeyup = horaPlanoAcao.onchange = minPlanoAcao.onchange = function () {
  modificarDinheiro(valorPlanoAcao, horaPlanoAcao, minPlanoAcao);
}

let valorImplantacao = document.getElementById('valorImplantacao');
let horaImplantacao = document.getElementById('horaImplantacao');
let minImplantacao = document.getElementById('minImplantacao');
horaImplantacao.onkeyup = minImplantacao.onkeyup = horaImplantacao.onchange = minImplantacao.onchange = function () {
  modificarDinheiro(valorImplantacao, horaImplantacao, minImplantacao);
}

let valorRelatorio = document.getElementById('valorRelatorio');
let horaRelatorio = document.getElementById('horaRelatorio');
let minRelatorio = document.getElementById('minRelatorio');
horaRelatorio.onkeyup = minRelatorio.onkeyup = horaRelatorio.onchange = minRelatorio.onchange = function () {
  modificarDinheiro(valorRelatorio, horaRelatorio, minRelatorio);
}

let valorTreinamento = document.getElementById('valorTreinamento');
let horaTreinamento = document.getElementById('horaTreinamento');
let minTreinamento = document.getElementById('minTreinamento');
horaTreinamento.onkeyup = minTreinamento.onkeyup = horaTreinamento.onchange = minTreinamento.onchange = function () {
  modificarDinheiro(valorTreinamento, horaTreinamento, minTreinamento);
}

let valorDpo = document.getElementById('valorDpo');
let horaDpo = document.getElementById('horaDpo');
let minDpo = document.getElementById('minDpo');
horaDpo.onkeyup = minDpo.onkeyup = horaDpo.onchange = minDpo.onchange = function () {
  modificarDinheiro(valorDpo, horaDpo, minDpo);
}

let valorValidacao = document.getElementById('valorValidacao');
let horaValidacao = document.getElementById('horaValidacao');
let minValidacao = document.getElementById('minValidacao');
horaValidacao.onkeyup = minValidacao.onkeyup = horaValidacao.onchange = minValidacao.onchange = function () {
  modificarDinheiro(valorValidacao, horaValidacao, minValidacao);
}

let valorMelhorias = document.getElementById('valorMelhorias');
let horaMelhorias = document.getElementById('horaMelhorias');
let minMelhorias = document.getElementById('minMelhorias');
horaMelhorias.onkeyup = minMelhorias.onkeyup = horaMelhorias.onchange = minMelhorias.onchange = function () {
  modificarDinheiro(valorMelhorias, horaMelhorias, minMelhorias);
}

let valorTotal = document.getElementById('valorTotal');
let horaTotal = document.getElementById('horaTotal');
let minTotal = document.getElementById('minTotal');

function atualizarTotal() {



  if (correto) {
    let somaValor = 0;
    let somaHora = 0;
    let somaMin = 0;

    somaValor += (parseInt(minColeta.value) + parseInt(horaColeta.value) * 60);
    somaHora += parseInt(horaColeta.value);
    somaMin += parseInt(minColeta.value);

    somaValor += (parseInt(minGap.value) + parseInt(horaGap.value) * 60);
    somaHora += parseInt(horaGap.value);
    somaMin += parseInt(minGap.value);

    somaValor += (parseInt(minPlanoAcao.value) + parseInt(horaPlanoAcao.value) * 60);
    somaHora += parseInt(horaPlanoAcao.value);
    somaMin += parseInt(minPlanoAcao.value);

    somaValor += (parseInt(minImplantacao.value) + parseInt(horaImplantacao.value) * 60);
    somaHora += parseInt(horaImplantacao.value);
    somaMin += parseInt(minImplantacao.value);

    somaValor += (parseInt(minRelatorio.value) + parseInt(horaRelatorio.value) * 60);
    somaHora += parseInt(horaRelatorio.value);
    somaMin += parseInt(minRelatorio.value);

    somaValor += (parseInt(minTreinamento.value) + parseInt(horaTreinamento.value) * 60);
    somaHora += parseInt(horaTreinamento.value);
    somaMin += parseInt(minTreinamento.value);

    somaValor += (parseInt(minDpo.value) + parseInt(horaDpo.value) * 60);
    somaHora += parseInt(horaDpo.value);
    somaMin += parseInt(minDpo.value);

    somaValor += (parseInt(minValidacao.value) + parseInt(horaValidacao.value) * 60);
    somaHora += parseInt(horaValidacao.value);
    somaMin += parseInt(minValidacao.value);

    somaValor += (parseInt(minMelhorias.value) + parseInt(horaMelhorias.value) * 60);
    somaHora += parseInt(horaMelhorias.value);
    somaMin += parseInt(minMelhorias.value);

    somaValor = somaValor * valorMinuto;

    let somaResultado = (somaHora * 60) + somaMin;
    let minResultado = somaResultado % 60;
    let horaResultado = parseInt(somaResultado / 60);

    valorTotal.innerText = somaValor.toLocaleString('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    });
    valorTotal.style.color = 'black';

    minTotal.innerHTML = minResultado;
    minTotal.style.color = 'black';

    horaTotal.innerHTML = horaResultado;
    horaTotal.style.color = 'black';
  } else {
    minTotal.innerHTML = '--';
    minTotal.style.color = 'red';

    horaTotal.innerHTML = '--';
    horaTotal.style.color = 'red';

    valorTotal.innerText = 'R$ --';
    valorTotal.style.color = 'red';
  }

}