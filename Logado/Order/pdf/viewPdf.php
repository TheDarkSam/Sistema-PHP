<?php

// Sessao
session_start();

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once '../../../conexao/sistemaConnect.php';
require_once '../../../plugins/mpdf/vendor/autoload.php';
require_once '../../operations/Formats.php';
require_once 'config/numeroPorExtenso.php';

header("Content-type: text/html; charset=utf-8");

function clear($input)
{
	global $connect;
	$var = mysqli_escape_string($connect, $input);
	$var = htmlspecialchars($var);
	return $var;
}

$existe = false;

if (isset($_POST['idOrcamento'])) {
	$idOrcamento = clear($_POST['idOrcamento']);
	$dados = array();

	$sql = "SELECT l.total, c.nomeEmpresa, c.nomeResponsavel, o.valorHora, o.situacao, o.dataValidado, p.totalOrcamento, p.valorEntrada, p.totParcelas FROM lgpd l INNER JOIN orcamento o ON l.idOrcamento = o.idOrcamento INNER JOIN cliente c ON c.idCliente = o.idCliente INNER JOIN pagamento p ON p.idOrcamento = o.idOrcamento AND o.idOrcamento = '{$idOrcamento}'";
	$resultado = mysqli_query($connect, $sql);
	if (mysqli_num_rows($resultado) > 0) {
		$dados = mysqli_fetch_array($resultado);
		$existe = true;
	}
}

// Verificação
if (!isset($_SESSION['logado']) || !isset($_SESSION['acesso']['orcamento']) || !$_SESSION['acesso']['orcamento']['visualizar'] || !$existe) {
	header('Location: ../../../index.php');
} else {
	pdf();
}

mysqli_close($connect);

function pdf()
{
	global $dados;
	global $idOrcamento;
	$formats = new Formats;	

	$data = utf8_encode(strftime('%d de %B de %Y', strtotime("today")));
	
	$nomeEmpresa = $dados['nomeEmpresa'];
	$nomeResponsavel = $dados['nomeResponsavel'];
	$totalMinutos = $dados['total'];
	$valorMinuto = round($dados['valorHora'] / 60, 2);
	$valorTotal = $totalMinutos * $valorMinuto;

	if ($dados['valorEntrada'] == 0 && $dados['totParcelas'] == 0) {
		$txtPagamento = "Pagamento não definido";
	} elseif ($dados['valorEntrada'] == 0) {
		$totParcelas = $dados['totParcelas'];

		$stringTotParcelas = numeroPorExtenso::valorPorExtenso($totParcelas, false, false);

		$valorParcela = round($valorTotal / $dados['totParcelas'], 2);
		$valorParcela = str_replace(",", ".", $valorParcela);
		$valorParcela = $formats->formatarDinheiro($valorParcela);

		$stringParcela = str_replace(".", ",", $valorParcela);
		$stringParcela = numeroPorExtenso::valorPorExtenso($stringParcela, true, false);


		$txtPagamento = "Sem entrada e " . $totParcelas . " (" . $stringTotParcelas . ") parcelas de " . $valorParcela . " (" . $stringParcela . ").";
	} elseif ($dados['totParcelas'] == 0) {
		$valorAVista = round($valorTotal, 2);
		$valorAVista = str_replace(",", ".", $valorAVista);
		$valorAVista = $formats->formatarDinheiro($valorAVista);

		$stringAVista = str_replace(".", ",", $valorAVista);;
		$stringAVista = numeroPorExtenso::valorPorExtenso($stringAVista, true, false);

		$txtPagamento = "Pagamento à vista de " . $valorAVista . " (" . $stringAVista . ").";
	} else {
		$valorEntrada = str_replace(",", ".", $dados['valorEntrada']);
		$valorEntrada = $formats->formatarDinheiro($valorEntrada);

		$stringEntrada = str_replace(".", ",", $dados['valorEntrada']);
		$stringEntrada = numeroPorExtenso::valorPorExtenso($stringEntrada, true, false);

		$valorParcela = round(($valorTotal - $dados['valorEntrada']) / $dados['totParcelas'], 2);
		$valorParcela = str_replace(",", ".", $valorParcela);
		$valorParcela = $formats->formatarDinheiro($valorParcela);

		$stringParcela = str_replace(".", ",", $valorParcela);
		$stringParcela = numeroPorExtenso::valorPorExtenso($valorParcela, true, false);

		$totParcelas = $dados['totParcelas'];
		$stringTotParcelas = numeroPorExtenso::valorPorExtenso($dados['totParcelas'], false, true);

		$txtPagamento = "Entrada de {$valorEntrada} (" . $stringEntrada . ") e " . $totParcelas . " (" . $stringTotParcelas . ") parcelas de " . $valorParcela . " (" . $stringParcela . ").";
	}


	$mpdf = new \Mpdf\Mpdf(['tempDir' => '../../../plugins/mpdf/tmp', 'mode' => 'utf-8', 'format' => 'A4-P', 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => -34, 'margin_bottom' => -34]);

	$mpdf->SetDisplayMode('fullwidth');

	$stylesheet = file_get_contents('config/css/style.css');

	$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
	$espacoTopo = "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";

	$mpdf->SetHTMLHeader("<img src='config/imgs/header.png' class='header'>");
	$mpdf->SetHTMLFooter("<img src='config/imgs/footer.png' class='footer'>");
	$mpdf->WriteHTML($espacoTopo);

	$html = "
	<html>
	<body>
	<h2 class='text-center'>Orçamento</h2>

	<div class='container'>
		<p class='text-justify'>
			<span class='negrito'>Cliente:</span> {$nomeEmpresa}<br> 
			<span class='negrito'>Respónsavel:</span> {$nomeResponsavel} <br> 
		</p>

		<p class='text-justify indent'>
		A The Forense é uma empresa especializada em segurança digital, com profissionais qualificados com 20 anos de experiência no mercado de Tecnologia da Informação, com ênfase em Perícia Computacional Forense, Segurança da Informação, Compliance Digital e Lei Geral de Proteção de Dados Pessoais, inscrito no CNPJ 31.440.194/0001-70 e sediada na Rua: Thomas Tajra, Nº 310, Sala 02, Edifício João Borges Caminha - Jóquei, Teresina, vem, com sua experiência em consultorias desta natureza,  apresentar sua proposta dos serviços descritos abaixo:
		</p>

		<table class='tabela'>
			<tr>
				<td class='td100'>
					Consultoria de Implementação da Lei Geral de Proteção de Dados Pessoais
				</td>
			</tr>
		</table>	

		<br>
	
		<p class='negrito'>ESTRATÉGIA DE EXECUÇÃO</p>
		<p class='paragrafo text-justify indent'>
		Nossa estratégia de execução incorpora metodologias comprovadas, proﬁssionais qualiﬁcados e abordagem baseada em pronta resposta para o gerenciamento dos resultados ﬁnais. Analise a seguir a descrição dos  nossos métodos de projeto, bem como seu desenvolvimento, linha do tempo de eventos propostos e motivos pelos quais sugerimos o desenvolvimento do projeto conforme descrito.
		</p>

		<br>
		
		<p class='negrito'>PROGRAMA DE CONFORMIDADE COM A LGPD:</p>
		<p class='paragrafo text-justify indent'>
		O Programa de Conformidade com a Lei Geral de Proteção de Dados Pessoais tem como objetivo a implantação dos processos necessários para a adequação das organizações com a Lei no.13.709/18, que regulamenta o tratamento de dados pessoais coletados. Todo o processo é construído em conjunto: Consultoria e Organização.</p>

		<p class='indent'>Este processo é dividido em 09 fases:</p>	

		<ul>
			<li>
				Coleta: A primeira etapa do programa consiste na realização de 3 workshops (Jurídico, Administrativo e Tecnologia da
				Informação), a fim de realizar a coleta qualitativa das informações necessárias para a identificação do estado atual
				da empresa. Cada workshop tem duração de 04 horas.
			</li>
			<li>
				Gap Analysis: Nesta etapa consiste em uma análise da Consultoria, na construção de um diagnóstico 360º, na
				identificação das lacunas entre o estado atual da organização e o estado em conformidade com a LGPD.
			</li>
		</ul>
	
	</div>
	<!-- ./ container -->";

	$mpdf->WriteHTML($html);
	$mpdf->AddPage();
	$mpdf->WriteHTML($espacoTopo);

	$html = "
	<div class='container'>
		<ul>
			<li>Planejamento: A metodologia 5W2H visa na construção do planejamento do projeto através de diretrizes que auxiliam nas estratégias e atividades com mais praticidade e clareza. Elaborado juntamente com profissionais especializados em LGPD.</li>

			<li>Implantação: Fases da Execução do Programa de Conformidade com a LGPD: Sensibilização (Reunião com os responsáveis pelo processo de implantação.),  Execução (Realização das atividades do planejamento.) e Acompanhamento (Monitoramento dos Consultores das ações a serem executadas.).</li>

			<li>Operador de Proteção de Dados(DPO): A Consultoria acompanha a seleção, preparação do DPO conforme a necessidade da Organização. Um Workshop de 04 horas sobre as boas práticas de conformidade com a LGPD.</li>

			<li>Treinamento: Capacitação de multiplicadores dos processos de conformidade com a LGPD.</li>

			<li>Relatório de Impacto: A Prestação de Conta do Programa de Conformidade com a LGPD, será realizado juntamente com o DPO, nesta etapa serão considerados: A descrição dos tipos de dados coletados; A metodologia utilizada para a coleta e para a garantia da segurança das informações; A análise do controlador com relação a medidas, salvaguardas e mecanismos de mitigação de risco adotados.</li>

			<li>Validação: Uma equipe especializada, da Consultoria, em avaliação de processos, realizará uma verificação das atividades implantadas para validar o programa.</li>

			<li>Melhorias: Esta etapa consiste nas correções, por parte da organização, aplicadas nos processos que apresentaram falhas.</li>
		</ul>
	 
	 	<br>
	 
		<p class='negrito'>GERENTE TÉCNICO:</p>

		<p class='negrito'>•	Raimundo Pereira da Cunha Neto</p>	 

		<p class='pColado'>CEO da empresa The Forense.</p>
		<p class='pColado'>Mestre em Engenharia de Eletricidade pela Universidade Federal do Maranhão.</p>
		<p class='pColado'>Pós Graduado em Redes de Computadores pela FSA.</p>
		<p class='pColado'>Graduado em Bacharelado em Ciência da Computação pela Universidade Estadual do Piauí.</p>
		<p class='pColado'>Professor da Faculdade Maurício de Nassau e Unirb.</p>
		<p class='pColado'>Cooordenador da Pós raduação em Segurança de Redes de Computadores da Estácio(2014 a 2018).</p>
		<p class='pColado'>Coautor do Livro Informática Forense.</p>
		<p class='pColado'>Membro Convidado da Comissão de Direito Digital- OAB-PI(2017 e 2018).</p>
		<p class='pColado'>Diretor de Tecnologia da Informação da FAETE(2002 a 2009).</p>
		<p class='pColado'>Perito Ad Hoc Computacional Forense.</p>
	 
	 </div>
	<!-- ./ container -->";

	$mpdf->WriteHTML($html);
	$mpdf->AddPage();
	$mpdf->WriteHTML($espacoTopo);

	$html = "
	<div class='container'>
		<p class='negrito'>RESULTADOS ESPERADOS:</p>

		<p class='indent'>O presente programa tem como finalidade a entrega dos resultados:</p>

		<ul>
			<li>Controles de Conformidade com a LGPD;</li>
			<li>Visão crítica sobre os desafios da proteção de dados pessoais;</li>
			<li>Formação de Multiplicadores em LGPD;</li>
			<li>Documentação Legal;</li>
			<li>Preparação do responsável pelas Operações de Proteção de Dados;</li>
			<li>Contribuição para redução de fraudes nos sistemas de informação;</li>
			<li>Organização do Relatório de Impacto.</li>	 
		</ul>

		<br>

		<p class='negrito'>ORÇAMENTO:</p>

		<p class='indent'>O presente programa tem como finalidade a entrega dos resultados:</p>

		<table class='tabla' border=1 cellspacing=0 cellpadding=2>
			<tr>
				<th>Item</th>
				<th>Valor Total</th>
			<tr>
				<td class='text-center'>Consultoria de Implementação da Lei Geral de Proteção de Dados Pessoais</td>
				<td align='center'>{$formats->formatarDinheiro($valorTotal)}</td>	
			</tr>
		</table>

		<br>

		<p class='negrito'>ABORDAGEM TÉCNICA DE PROJETO:</p>

		<p class='indent text-justify'>
		O serviço realizado segue os padrões da Lei de Proteção de Dados Pessoais, Lei nº 13.709/2018, da ISO 27001 - Sistema de Gestão de Segurança da Informação, ISO 27002 - Boas práticas para a Gestão da Segurança da Informação e ISO 27701
		- Sisema de Gestão de privacidade da informação, além de utilizar ferramentas exclusivas, sempre com o objetivo de garantir a qualidade e conﬁabilidade possíveis para o serviço realizado com total transparência junto ao Cliente.	
		</p>

		<br>

		<p class='negrito'>CONDIÇÕES DE PAGAMENTO</p>

		<p class='text-justify'>{$txtPagamento}</p>

		<br>

		<p class='text-right'>{$data}</p>

		<div class='text-right'><img src='config/imgs/assinatura.jpeg' /></div>

		<p align=right>Raimundo Pereira da Cunha Neto Eireli</p>

	</div>
	<!-- ./ container -->";

	$mpdf->WriteHTML($html);

	$mpdf->SetTitle("PDF Orçamento");
	$mpdf->Output("Orcamento{$idOrcamento}.pdf", 'I');	
}
