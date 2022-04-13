function validator() {
  let telefoneFixo = document.getElementById("telefone_fixo").value.length;
  let celular = document.getElementById("celular").value.length;
  let whatsapp = document.getElementById("whatsapp").value.length;
  let email = document.getElementById("email").value.length;

  if (telefoneFixo > 0 && telefoneFixo != 14) {
    alert("Digite o telefone fixo corretamente!");
    document.formCustomer.telefone.focus();
    return false;
  }

  if (celular > 0 && celular != 15) {
    alert("Digite o celular corretamente!");
    document.formCustomer.celular.focus();
    return false;
  }

  if (whatsapp > 0 && whatsapp != 15) {
    alert("Digite o whatsapp corretamente!");
    document.formCustomer.whatsapp.focus();
    return false;
  }

  if (telefoneFixo == 0 && celular == 0 && whatsapp == 0 && email == 0) {
    alert("Prencha ao mesmo um campo de contato");
    return false;
  }


  return true;
}