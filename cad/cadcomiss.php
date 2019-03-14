<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Comissão";
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

	$tcomiss = array('O' => 'Operação', 'P' => 'Porcentagem');
	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Funcionário',3);
	$formulario->selectfunc($pempresa,'Todos');
	$formulario->label('Serviço',3);	
	$formulario->select('tpserv',$res);
	$formulario->label('Tipo de Comissão',3);	
	$formulario->select('tpcomiss',$tcomiss);
	$formulario->label('Valor da Comissão',3);
	$formulario->input('real','vlserv','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$tpserv = $_GET['tpserv'];		
	$tpcomiss = $_GET['tpcomiss'];	
	$funci = $_GET['funci'];
	$vlserv = $_GET['vlserv'];			
	$vlserv = str_replace(",",".","$vlserv");  // troca virgula por ponto
	$dtcriacao = date('Y-m-d');
	
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar("INSERT INTO `cad_comiss`(`ID_EMP`, `ID_FUNC`, `ID_SERV`,`TP_COMISS`,`VL_PAGO`, `STATUS`, `DT_INCLUSAO`, `USER_INCLUSAO`) VALUES ('$pempresa','$funci','$tpserv','$tpcomiss','$vlserv','A','$dtcriacao','$puser')",'insert');		
}

if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Funcionário',3);
	$formulario->selectfunc($pempresa,'todos');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$funci = isset($_GET['funci']) ? $_GET['funci'] : '';

		$query = "SELECT A.ID_COMISS AS COMISSAO,
		A.ID_FUNC AS FUNCIONARIO,
		B.NOME AS SERVICO,
		A.TP_COMISS AS TPCOMISS,
		A.VL_PAGO AS VALOR,
		A.STATUS AS STATUS,
		DATE_FORMAT(A.DT_INCLUSAO, '%d/%m/%Y') AS INCLUSAO,
		A.USER_INCLUSAO AS USER
		FROM CAD_COMISS A, CAD_TIPO_SERV B
		WHERE A.ID_EMP = '$pempresa' 
		AND A.ID_SERV = B.ID_SERV
		AND A.STATUS = 'A'";

		if($funci <> 'Todos') $query .=" AND A.ID_FUNC = '$funci'"; 
		// die($query); 	

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$return = $envi->Executar($query,'');
	// die($query);

		$i = 0;
		while ($row = mysqli_fetch_array($return)) {
			$idcomiss[$i] = $row['COMISSAO'];
			$funcionario[$i] = $row['FUNCIONARIO'];
			$servico[$i] = $row['SERVICO'];
			$qtpcomiss[$i] = $row['TPCOMISS'];
			$vlpago[$i] = $row['VALOR'];
			$status[$i] = $row['STATUS'];
			$dtinclusao[$i] = $row['INCLUSAO'];
			$usuario[$i] = $row['USER'];
			$dados[$i] = array('1' => $idcomiss[$i], '2' => $funcionario[$i], '3' =>$servico[$i], '4' => $qtpcomiss[$i], '5' => $vlpago[$i], '6' => $status[$i], '7' => $dtinclusao[$i], '8' => $usuario[$i] );
			$i++;

		}
	// die(print_r($dados));
		$coluna = array('1' => 'ID','2'=> 'Funcionário', '3'=>'Serviço', '4'=>'Tipo de Comissão', '5' =>'Valor', '6' =>'Status', '7' =>'Dt. Cadastro', '8' =>'Cad. Por ');
		$painel = new painel();
		$painel->estrutura(12,'Cadastro de Comissão',$coluna,$dados,'sim');
	}

}

if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT A.ID_COMISS AS ID,
	A.ID_FUNC,
	B.ID_SERV,
	A.TP_COMISS AS TIPO,
	A.VL_PAGO AS VALOR,
	A.STATUS,
	A.DT_INCLUSAO AS DATA,
	A.USER_INCLUSAO AS USER
	FROM cad_comiss A, cad_tipo_serv B
	WHERE     A.ID_EMP = B.ID_EMP
	AND A.ID_SERV = B.ID_SERV
	AND a.ID_COMISS = '$id'
	AND a.ID_EMP = '$pempresa'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$qid = $row['ID'];
		$qnome = $row['ID_SERV'];
		$qtipo = $row['TIPO'];
		$qvalor = $row['VALOR'];
	}

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$retorno = $envi->Executar("SELECT ID_SERV, NOME FROM CAD_TIPO_SERV WHERE ID_EMP = '$pempresa'AND STATUS = 'A' ORDER BY NOME",'');

	while ($row = mysqli_fetch_array($retorno)) {

		if($row['ID_SERV'] == $qnome ){

			$qdados[$row['ID_SERV']] = $row['NOME'];
		}
	}
	mysqli_data_seek($retorno, 0);

	while ($row = mysqli_fetch_array($retorno)) {

		if($row['ID_SERV'] <> $qnome ){

			$qdados[$row['ID_SERV']] = $row['NOME'];
		}
	}

	if($qtipo == 'O')$tcomiss = array('O' => 'Operação', 'P' => 'Porcentagem');
	if($qtipo == 'P')$tcomiss = array('P' => 'Porcentagem','O' => 'Operação');

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$qid);
	$formulario->label('Serviço',3);	
	$formulario->select('tpserv',$qdados);
	$formulario->label('Tipo de Comissão',3);	
	$formulario->select('tpcomiss',$tcomiss);
	$formulario->label('Valor da Comissão',3);
	$formulario->input('real','vlserv',$qvalor);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}
if(array_key_exists('Editar',$_GET)){

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$tpserv = isset($_GET['tpserv']) ? $_GET['tpserv'] : '';
	$tpcomiss = isset($_GET['tpcomiss']) ? $_GET['tpcomiss'] : '';
	$vlserv = isset($_GET['vlserv']) ? $_GET['vlserv'] : '';
	$vlserv = str_replace(",",".","$vlserv"); // trocando virgula por ponto
	$dt = date('Y-m-d');

	$query = "UPDATE cad_comiss
			   SET ID_SERV = '$tpserv',
			       TP_COMISS = '$tpcomiss',
			       VL_PAGO = '$vlserv',
			       DT_INCLUSAO = '$dt',
			       USER_INCLUSAO = '$puser'
			 WHERE id_comiss = '$id'
			 AND ID_EMP = '$pempresa'
			 ";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "UPDATE cad_comiss
			   SET STATUS = 'I',
			   	   USER_INCLUSAO = '$puser'			       
			 WHERE id_comiss = '$id'
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
	alerta::open('verde','Dados Deletados com Sucesso! :) ','');
	
}

if(array_key_exists('Voltar',$_GET)){

	$url = $_SESSION['ROTA'];

	header("Location:$url");
}


rodape();
