<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Tipos de Serviços";
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
	$vl = array('O' => 'Operação','P'=> 'Porcentagem' );
	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Nome do Serviço',3);
	$formulario->input('generico','nomeserv','');
	$formulario->label('Tipo de Comissão ',2);
	$formulario->select('tpoperação',$vl);
	$formulario->label('Valor do Serviço',3);
	$formulario->input('real','vlserv','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$nomeserv = $_GET['nomeserv'];
	$tpserv = $_GET['tpoperação'];
	$vlserv = $_GET['vlserv'];		
	$vlserv = str_replace(",",".","$vlserv");  // remove ponto
	$dtcriacao = date('Y-m-d');
	
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar("INSERT INTO `cad_tipo_serv`(`ID_EMP`, `ID_USER`, `NOME`, `TIPO`, `VL_SERV`, `DT_CRIAC`, `STATUS`) VALUES ('$pempresa','$puser','$nomeserv','$tpserv','$vlserv','$dtcriacao','A')",'insert');		
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
	$formulario->label('Serviço',3);
	$formulario->select('tpservico',$res);
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$tpservico = isset($_GET['tpservico']) ? $_GET['tpservico'] : '';

		$query = "SELECT A.ID_SERV AS ID,
		A.NOME AS NOME,
		A.TIPO AS TIPO,
		A.VL_SERV AS VALOR,
		DATE_FORMAT(A.DT_CRIAC, '%d/%m/%Y') AS DATA,
		A.ID_USER AS USER
		FROM cad_tipo_serv A
		WHERE A.ID_EMP = '$pempresa'
		AND A.STATUS = 'A'";

		if($tpservico <> 'Todos') $query .=" AND A.ID_SERV = '$tpservico'"; 

	// die($query); 	

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$return = $envi->Executar($query,'');
// die($query);

		$i = 0;
		while ($row = mysqli_fetch_array($return)) {
			$idserv[$i] = $row['ID'];
			$nome[$i] = $row['NOME'];
			$tpserv[$i] = $row['TIPO'];
			$vl[$i] = $row['VALOR'];
			$data[$i] = $row['DATA'];
			$user[$i] = $row['USER'];
			$dados[$i] = array('1' => $idserv[$i], '2' => $nome[$i], '3' =>$tpserv[$i], '4' =>$vl[$i], '5' => $data[$i], '6' => $user[$i]);
			$i++;

		}
// die(print_r($dados));
		$coluna = array('1' => 'ID','2'=> 'Nome Serviço', '3'=>'Tipo','4'=>'Valor', '5' =>'Dt. Cadastro', '6' =>'Cad. Por');
		$painel = new painel();
		$painel->estrutura(12,'Tipo de Serviços',$coluna,$dados,'sim');
	}

}

if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT ID_USER AS USER,
			       ID_SERV AS SERV,
			       NOME,
			       TIPO,
			       VL_SERV AS VL
			  FROM cad_tipo_serv
			  WHERE ID_EMP = '$pempresa'
			  AND ID_SERV = '$id'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');
	// die($query);
	while ($row = mysqli_fetch_array($return)) {
		$qserv = $row['SERV'];
		$qnome = $row['NOME'];
		$qtipo = $row['TIPO'];
		$qvl = $row['VL'];
	}
	// die($qtipo);
	$qvl = str_replace(".",",","$qvl"); // trocando virgula por ponto
	if($qtipo == 'O')$tcomiss = array('O' => 'Operação', 'P' => 'Porcentagem');
	if($qtipo == 'P')$tcomiss = array('P' => 'Porcentagem','O' => 'Operação');

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$id);
	$formulario->label('Nome do Serviço',3);
	$formulario->input('generico','nomeserv',$qnome);
	$formulario->label('Serviço',3);	
	$formulario->select('tpserv',$tcomiss);
	$formulario->label('Valor do serviço',3);
	$formulario->input('real','vlserv',$qvl);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}
if(array_key_exists('Editar',$_GET)){

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$nomeserv = isset($_GET['nomeserv']) ? $_GET['nomeserv'] : '';
	$tpserv = isset($_GET['tpserv']) ? $_GET['tpserv'] : '';
	$vlserv = isset($_GET['vlserv']) ? $_GET['vlserv'] : '';
	$vlserv = str_replace(",",".","$vlserv"); // trocando virgula por ponto
	$dt = date('Y-m-d');

	$query = "UPDATE cad_tipo_serv
			   SET ID_USER = '$puser',
			       NOME = '$nomeserv',
			       TIPO = '$tpserv',
			       VL_SERV = '$vlserv',
			       DT_CRIAC = '$dt'
			 WHERE ID_EMP = '$pempresa' 
			 AND ID_SERV = '$id'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}
if(array_key_exists('delete',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "UPDATE CAD_TIPO_SERV 
				SET STATUS = 'I'
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



rodape();