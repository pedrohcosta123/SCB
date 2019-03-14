<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Serviços de Clientes";
carrega_topo($pempresa,$puser,'Cadastro',$titulo,'glyphicon glyphicon-plus');


if(array_key_exists('menu',$_GET)){

	$url = UrlAtual();
	$_SESSION['ROTA'] = $url;
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('laranja','Cadastro','1','glyphicon glyphicon-plus','cad');
	$formulario->btn('azul','Consultar','3','glyphicon glyphicon-search','consul');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('insert',$_GET)){
		$formulario->opendiv('container-fluid col-md-12',1);
		if($_GET['insert'] == 'false'){
			alerta::open('vermelho','Ops!! algo deu errado :(','Os dados não foram salvos, por favor tente novamente!');
		}
		if($_GET['insert'] == 'true'){
			alerta::open('verde','Dados Salvos com Sucesso!','');
		}
		$formulario->closediv();
	}
}

if(array_key_exists('Cadastro',$_GET)){

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$retorno = $envi->Executar("SELECT ID_SERV, NOME FROM CAD_TIPO_SERV WHERE ID_EMP = '$pempresa'AND STATUS = 'A' ORDER BY NOME",'');

	while ($row = mysqli_fetch_array($retorno)) {
		$res[$row['ID_SERV']] = $row['NOME'];
	}

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->opendiv('col-sm-12 col-md-12','0');
	$formulario->label('CPF do Cliente',3);
	$formulario->input('cpf','cpfcli','');
	$formulario->closediv();
	$formulario->divid('teste','linhas col-sm-12 col-md-12'); 	
	$formulario->label('Serviço',3);
	$formulario->selectt('tpservico[]',$res);
	$formulario->label('Valor do Serviço',3);
	$formulario->input('real','vlserv[]','');
	$formulario->opendiv('col-sm-2 col-md-2',1);
	$formulario->btn('removerCampo btn btn-danger','Remover','1', 'glyphicon glyphicon-minus', 'jquery');
	$formulario->closediv();
	$formulario->closediv();
	$formulario->opendiv('col-sm-12 col-md-12',0);
	$formulario->btn('adicionarCampo btn btn-primary','Adinicionar','1','glyphicon glyphicon-plus','jquery');
	$formulario->closediv();
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$idtiposerv = $_GET['tpservico'];
	$cliente = $_GET['cpfcli'];
	$cliente = removecaracteres($cliente);
	$vlserv = $_GET['vlserv'];
	$dtcriacao = date('Y-m-d');

	foreach ($vlserv as $key => $value) {
		$fvlserv[$key] = $value;
		$fvlserv[$key] = str_replace(",",".",$value);  // remove traço
	}
	$contador = 0;
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();

	foreach ($fvlserv as $key => $value) {
		$envi->Executar("INSERT INTO `cad_serv`(`ID_EMP`, `ID_TIPO_SERV`, `CPF_CLI`, `VL_SERV`, `DT_INCLUSAO`, `USER`) VALUES ('$pempresa','$idtiposerv[$contador]','$cliente','$value','$dtcriacao','$puser')",'insert');
		$contador++;
	}

}

if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$retorno = $envi->Executar("SELECT ID_SERV, NOME FROM CAD_TIPO_SERV WHERE ID_EMP = '$pempresa'AND STATUS = 'A' ORDER BY NOME",'');

	while ($row = mysqli_fetch_array($retorno)) {
		$res[$row['ID_SERV']] = $row['NOME'];
	}
	$res['Todos'] = 'Todos';

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Data Inicial',3);
	$formulario->input('data','dtinicial','');
	$formulario->label('Data Final',3);
	$formulario->input('data','dtfinal','');
	$formulario->label('Serviço',3);
	$formulario->select('tpservico',$res);
	$formulario->label('CPF do Cliente',3);
	$formulario->input('cpf','cpfcli','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$dtinicial = isset($_GET['dtinicial']) ? $_GET['dtinicial'] : '';
		$dtfinal = isset($_GET['dtfinal']) ? $_GET['dtfinal'] : '';
		$tpservico = isset($_GET['tpservico']) ? $_GET['tpservico'] : '';
		$cpfcli = isset($_GET['cpfcli']) ? $_GET['cpfcli'] : '';
		$cpfcli = removecaracteres($cpfcli);

		$query = "SELECT A.ID_SERV AS ID,
		B.NOME AS NOME,
		A.CPF_CLI AS CPF,
		A.VL_SERV AS VALOR,
		A.CPF_CLI AS CPF,
		DATE_FORMAT(A.DT_INCLUSAO, '%d/%m/%Y') AS DATA,
		A.USER AS USER
		FROM cad_serv A, cad_tipo_serv B
		WHERE A.ID_TIPO_SERV = B.ID_SERV 
		AND A.ID_EMP = B.ID_EMP 
		AND A.ID_EMP = '$pempresa'";

		if($dtinicial <> '') $query .=" AND DATE_FORMAT(A.DT_INCLUSAO,'%d/%m/%Y') >= '$dtinicial'"; 
		if($dtfinal <> '') $query .=" AND DATE_FORMAT(A.DT_INCLUSAO,'%d/%m/%Y') <= '$dtfinal'";
		if($cpfcli <> '') $query .=" AND A.CPF_CLI like ('%$cpfcli%') ";  
		if($tpservico <> 'Todos' and  $tpservico <> '') $query .=" AND B.ID_SERV = '$tpservico'"; 

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$return = $envi->Executar($query,'');

		$i = 0;
		while ($row = mysqli_fetch_array($return)) {
			$idserv[$i] = $row['ID'];
			$nome[$i] = $row['NOME'];
			$vl[$i] = $row['VALOR'];
			$cpf[$i] = $row['CPF'];
			$data[$i] = $row['DATA'];
			$user[$i] = $row['USER'];
			$dados[$i] = array('1' => $idserv[$i], '2' => $nome[$i], '3' =>$cpf[$i], '4' =>$vl[$i], '5' => $data[$i], '6' => $user[$i]);
			$i++;
		}

		$coluna = array('1' => 'ID','2'=> 'Serviço', '3'=>'CPF','4'=>'Valor do Serviço', '5' =>'Data', '6' =>'Cad. Por');
		$painel = new painel();
		$painel->estrutura(12,'Serviços',$coluna,$dados,'sim');
	}
}

if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT B.NOME AS SERVICO,
		      A.CPF_CLI AS CPF, 
		      A.VL_SERV AS VL
		  FROM CAD_SERV A, cad_tipo_serv B
		 WHERE     A.ID_TIPO_SERV = B.ID_SERV
		       AND A.ID_EMP = B.ID_EMP
		       AND A.ID_EMP = '$pempresa'
		       AND A.ID_SERV = '$id'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$qnome = $row['SERVICO'];
		$qcpf = $row['CPF'];
		$qvl = $row['VL'];
	}

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$retorno = $envi->Executar("SELECT ID_SERV, NOME FROM CAD_TIPO_SERV WHERE ID_EMP = '$pempresa'AND STATUS = 'A' ORDER BY NOME",'');

	while ($row = mysqli_fetch_array($retorno)) {

		if($row['NOME'] == $qnome ){

			$qdados[$row['ID_SERV']] = $row['NOME'];
		}
	}
	mysqli_data_seek($retorno, 0);

	while ($row = mysqli_fetch_array($retorno)) {

		if($row['ID_SERV'] <> $qnome ){

			$qdados[$row['ID_SERV']] = $row['NOME'];
		}
	}

	
	$qvl = str_replace(".",",","$qvl"); // trocando virgula por ponto

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$id);
	$formulario->label('CPF',3);
	$formulario->input('cpf','cpfcli',$qcpf);
	$formulario->label('Serviço',3);	
	$formulario->select('tpserv',$qdados);
	$formulario->label('Valor do serviço',3);
	$formulario->input('real','vlserv',$qvl);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}
if(array_key_exists('Editar',$_GET)){

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$cpfcli = isset($_GET['cpfcli']) ? $_GET['cpfcli'] : '';
	$tpserv = isset($_GET['tpserv']) ? $_GET['tpserv'] : '';
	$vlserv = isset($_GET['vlserv']) ? $_GET['vlserv'] : '';
	$vlserv = str_replace(",",".","$vlserv"); // trocando virgula por ponto
	$dt = date('Y-m-d');

	$query = "UPDATE cad_serv
			   SET ID_TIPO_SERV = '$tpserv',
			       CPF_CLI = '$cpfcli',
			       VL_SERV = '$vlserv',
			       DT_INCLUSAO = '$dt',
			       USER = '$puser'
			 WHERE ID_SERV = '$id' 
			 AND ID_EMP = '$pempresa'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "DELETE FROM CAD_SERV
				WHERE ID_SERV = '$id'
				AND ID_EMP = '$pempresa'
			 ";
			 
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'delete');	

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('laranja','Voltar','1','glyphicon glyphicon-arrow-left','return');
	$formulario->closediv();
	$formulario->closeform();
	alerta::open('verde','Dados Deletados Com Sucesso! :) ','');
	
}

if(array_key_exists('Voltar',$_GET)){

	$url = $_SESSION['ROTA'];

	header("Location:$url");
}

$query = "SELECT ID_SERV 
			FROM CAD_TIPO_SERV
			WHERE ID_EMP = '$pempresa'
			AND TIPO = 'O'";
		 
$sql =  new Conexao();
$sql->Abrir();
$envi = new Comando();
$dados = $envi->Executar($query,'');

while ($row = mysqli_fetch_array($dados)) {
	$id[] = $row['ID_SERV'];
}
$script = '';
foreach ($id as $key => $value) {
	$script .= " 
	    if(sele[i].value ==".$value."){
	    	input[i].readOnly = true;	    	
		}
		";
}

$query1 = "SELECT ID_SERV 
			FROM CAD_TIPO_SERV
			WHERE ID_EMP = '$pempresa'
			AND TIPO = 'P'";
		 
$sql =  new Conexao();
$sql->Abrir();
$envi = new Comando();
$dados = $envi->Executar($query1,'');

while ($row = mysqli_fetch_array($dados)) {
	$id1[] = $row['ID_SERV'];
}
foreach ($id1 as $key => $value) {
	$script .="
		if(sele[i].value == ".$value."){
			input[i].readOnly = false;
		}";
}

echo "<script>
function alterar(){	
 
 var input = document.querySelectorAll('.valor','.form-control');
 var sele = document.querySelectorAll('.sele','.form-control');
 
 	for(var i = 0; i < sele.length;i++){".
		$script."

	}
}
</script>";

echo"<script>
window.onload = function(){	
 
 var input = document.querySelectorAll('.valor','.form-control');
 var sele = document.querySelectorAll('.sele','.form-control');
 
 	for(var i = 0; i < sele.length;i++){".
		$script."

	}
}
</script>";

echo "<script type='text/javascript'>
		$(function () {
			function removeCampo() {
				$('.removerCampo').unbind('click');
				$('.removerCampo').bind('click', function () {
					if($('div.linhas').length > 1){
						$(this).parent().parent().remove();
					}
				});
			}

			$('.adicionarCampo').click(function () {
				novoCampo = $('div.linhas:first').clone();
				novoCampo.find('input').val('');
				novoCampo.insertAfter('div.linhas:last');
				removeCampo();
				alterar();
				
			});
		});
		</script>";

rodape();