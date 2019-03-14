<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Menus";
carrega_topo($pempresa,$puser,'sistema',$titulo,'glyphicon glyphicon-plus');


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

	$idmenu['0'] = 'Home';
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$qdados = $envi->Executar("SELECT ID_MENU AS ID, NOME_MENU AS NOME FROM menu WHERE ID_MENU_PAI = '0'",'');

	while ($row = mysqli_fetch_array($qdados)) {
		$idmenu[$row['ID']] = $row['NOME'];

	}

	$acesso = array('1' =>'Administrador' ,'2' => 'Cliente', '3' =>'Funcionário'); 
	$tiposmenus = array('1' =>'PAI' ,'2' => 'FILHO'); 

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Nível de Acesso',2);
	$formulario->select('tipoacesso',$acesso);
	$formulario->label('Tipo de Menu',2);
	$formulario->select('tipomenu',$tiposmenus);
	$formulario->label('ID do menu pai',2);
	$formulario->select('idpai',$idmenu);
	$formulario->label('Nome do Menu',6);
	$formulario->input('generico','nome','');
	$formulario->label('Descrição',6);
	$formulario->input('generico','descr','');
	$formulario->label('Rota',6);
	$formulario->input('url','url','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$tpmenu = isset($_GET['tipomenu']) ? $_GET['tipomenu'] : '';
	$idpai = isset($_GET['idpai']) ? $_GET['idpai'] : '';
	$nome = isset($_GET['nome']) ? $_GET['nome'] : '';
	$descr = isset($_GET['descr']) ? $_GET['descr'] : '';
	$nameurl = isset($_GET['url']) ? $_GET['url'] : '';
	$tpacesso = isset($_GET['tipoacesso']) ? $_GET['tipoacesso'] : '';
	$tpacesso = isset($_GET['tipoacesso']) ? $_GET['tipoacesso'] : '';

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar("INSERT INTO `menu`(`TIPO_MENU`, `ID_MENU_PAI`, `NOME_MENU`, `DESCR_MENU`, `ROTA_MENU`, `TIPO_ACESSO`, `MENU_STATUS`) VALUES ('$tpmenu','$idpai','$nome','$descr','$nameurl','$tpacesso','1')",'insert');



}

if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){

	$idmenu['0'] = 'Home';
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$ddados = $envi->Executar("SELECT ID_MENU AS ID, NOME_MENU AS NOME FROM menu WHERE ID_MENU_PAI = '0'",'');

	while ($row = mysqli_fetch_array($ddados)) {
		$idmenu[$row['ID']] = $row['NOME'];

	}

	$acesso = array('Todos' => 'Todos','1' =>'Administrador' ,'2' => 'Cliente', '3' =>'Funcionário');
	$tiposmenus = array('Todos' => 'Todos','1' =>'PAI' ,'2' => 'FILHO'); 

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Nível de Acesso',2);
	$formulario->select('tipoacesso',$acesso);
	$formulario->label('Tipo de Menu',2);
	$formulario->select('tipomenu',$tiposmenus);
	$formulario->label('Nome do Menu',3);
	$formulario->input('generico','nome','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$tipoacesso = isset($_GET['tipoacesso']) ? $_GET['tipoacesso'] : '';
		$tipomenu = isset($_GET['tipomenu']) ? $_GET['tipomenu'] : '';
		$nome = isset($_GET['nome']) ? $_GET['nome'] : '';

		$query = "SELECT ID_MENU AS ID,
		TIPO_MENU AS MENU,
		NOME_MENU AS NOME,
		ID_MENU_PAI AS PAI,
		DESCR_MENU AS DESCR,
		ROTA_MENU AS ROTA,
		TIPO_ACESSO AS ACESSO,
        CASE WHEN MENU_STATUS = 1 THEN 'ATIVO' ELSE 'INATIVO' END AS STATUS
		FROM MENU
		WHERE 1=1";

		if($tipoacesso <> ''  and $tipoacesso <> 'Todos') $query .= " AND TIPO_ACESSO = '$tipoacesso' ";
		if($tipomenu <> '' and $tipomenu <> 'Todos') $query .= " AND TIPO_MENU = '$tipomenu'";
		if($nome <> '' ) $query .= " AND NOME_MENU LIKE '%$norme%'";

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$rquery = $envi->Executar($query,'');
	 // die(print_r($rquery));

		$i = 0;
		while ($row = mysqli_fetch_array($rquery)) {
			$qid[$i] = $row['ID'];
			$qtpmenu[$i] = $row['MENU'];
			$qnome[$i] = $row['NOME'];
			$qidpai[$i] = $row['PAI'];
			$qdescr[$i] = $row['DESCR'];
			$qrota[$i] = $row['ROTA'];
			$qacesso[$i] = $row['ACESSO'];
			$qstatus[$i] = $row['STATUS'];	

			$dados[$i] = array('1' => $qid[$i], '2' => $qtpmenu[$i], '3' => $qnome[$i], '4' => $qidpai[$i], '5' => $qdescr[$i], '6' => $qrota[$i], '7' => $qacesso[$i], '8' => $qstatus[$i] );
			$i++;

		}

		$coluna = array('1' => 'ID','2'=> 'Menu', '3'=>'Nome', '4' =>'Pai', '5' =>'Descr', '6' =>'Rota', '7' =>'Acesso', '8' =>'Status');
		$painel = new painel();
		$painel->estrutura(12,'Cadastro de Empresas',$coluna,$dados,'sim');
	}

}

if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT ID_MENU,
			       TIPO_MENU,
			       ID_MENU_PAI,
			       NOME_MENU,
			       DESCR_MENU,
			       ROTA_MENU,
			       TIPO_ACESSO
			  FROM menu
			  WHERE ID_MENU = '$id'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$qptmenu = $row['TIPO_MENU'];
		$qidpai = $row['ID_MENU_PAI'];
		$qnome = $row['NOME_MENU'];
		$qdescr = $row['DESCR_MENU'];
		$qrota = $row['ROTA_MENU'];
		$qtpacesso = $row['TIPO_ACESSO'];
	}
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$ddados = $envi->Executar("SELECT ID_MENU AS ID, NOME_MENU AS NOME FROM menu WHERE ID_MENU_PAI = '0'",'');

	$idmenu[$id] = $qnome;
	while ($row = mysqli_fetch_array($ddados)) {

		if($row['NOME']<> $qnome){
			$idmenu[$row['ID']] = $row['NOME'];
		}

	}

	if($qnome <> 'Home')$idmenu['0'] = 'Home';

	if($qtpacesso == '1')$acesso = array('1' =>'Administrador' ,'2' => 'Cliente', '3' =>'Funcionário');
	if($qtpacesso == '2')$acesso = array('2' =>'Cliente' ,'1' => 'Administrador', '3' =>'Funcionário');
	if($qtpacesso == '3')$acesso = array('3' =>'Funcionário' ,'2' => 'Cliente', '1' =>'Administrador');

	if($qptmenu == '1')	$tiposmenus = array('1' =>'PAI' ,'2' => 'FILHO'); 
	if($qptmenu == '2')	$tiposmenus = array('2' => 'FILHO','1' =>'PAI' );

	// print_r($qdescr);
	// die();
	
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$id);	
	$formulario->label('Nível de Acesso',2);
	$formulario->select('tipoacesso',$acesso);
	$formulario->label('Tipo de Menu',2);
	$formulario->select('tipomenu',$tiposmenus);
	$formulario->label('ID do menu pai',3);
	$formulario->select('idpai',$idmenu);
	$formulario->label('Nome do Menu',6);
	$formulario->input('generico','nome',$qnome);
	$formulario->label('Descrição',6);
	$formulario->input('generico','descr',$qdescr);
	$formulario->label('Rota',6);
	$formulario->input('url','url',$qrota);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Editar',$_GET)){

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$acesso = isset($_GET['tipoacesso']) ? $_GET['tipoacesso'] : '';
	$tipomenu = isset($_GET['tipomenu']) ? $_GET['tipomenu'] : '';
	$idpai = isset($_GET['idpai']) ? $_GET['idpai'] : '';
	$nome = isset($_GET['nome']) ? $_GET['nome'] : '';
	$descr = isset($_GET['descr']) ? $_GET['descr'] : '';
	$url = isset($_GET['url']) ? $_GET['url'] : '';
	$dt = date('Y-m-d');

	$query = "UPDATE menu
			   SET TIPO_MENU = '$tipomenu',
			       ID_MENU_PAI = '$idpai',
			       NOME_MENU = '$nome',
			       DESCR_MENU = '$descr',
			       ROTA_MENU = '$url',
			       TIPO_ACESSO = '$acesso'
			 WHERE ID_MENU = '$id'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "DELETE FROM MENU WHERE ID_MENU = '$id'";
	// die($query);

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'delete');	
}

rodape();