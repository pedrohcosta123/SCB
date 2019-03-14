<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de usuários";
carrega_topo($pempresa,$puser,'Usuarios',$titulo,'glyphicon glyphicon-plus');


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

	$tpacesso = array('1' => 'Administrador','2'=> 'Cliente', '3' => 'Funcionário' );

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Nome do usuario',3);
	$formulario->input('generico','nameuser','');
	$formulario->label('senha',3);
	$formulario->input('password','pass','');
	$formulario->label('Perfil de Acesso',2);
	$formulario->select('tipo',$tpacesso);
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$nome = $_GET['nameuser'];
	$desc = $_GET['pass'];
	$tipo = $_GET['tipo'];
	$dtcriacao = date('Y-m-d');

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar("INSERT INTO `user`(`ID_EMP`, `USER`, `SENHA`, `CRIADOR`, `DT_CRIACAO`, `TIPO`, `USER_STATUS`) VALUES ('$pempresa','$nome','$desc','$puser','$dtcriacao','$tipo','A')",'insert');



}

if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){

	$query = "SELECT USER FROM user
				WHERE ID_EMP = '$pempresa'
				AND TIPO >= '$ptipo'
				AND USER <> '$puser'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$rquery = $envi->Executar($query,'');
	$quser['Todos'] = 'Todos';
	while ($row = mysqli_fetch_array($rquery)) {
		$quser[$row['USER']] = $row['USER'];
		
	}

	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('Nome do Usuário',3);
	$formulario->select('usuario',$quser);
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$nome = isset($_GET['usuario']) ? $_GET['usuario'] : '';

		$query = "SELECT A.USER AS NOME,
				       A.CRIADOR AS CRIADOR,
				       DATE_FORMAT(A.DT_CRIACAO, '%d/%m/%Y') AS CRIACAO,
				       CASE
				          WHEN A.USER_STATUS = 'A' THEN 'Ativo'
				          WHEN A.USER_STATUS = 'I' THEN 'Inativo'
				          ELSE 'Bloqueado'
				       END
				          AS STATUS,
				       CASE
				          WHEN A.TIPO = 1 THEN 'Administrador'
				          WHEN A.TIPO = 2 THEN 'Cliente'
				          ELSE 'Funcionário'
				       END
				          AS TIPO,
				       DATE_FORMAT(A.DT_CRIACAO,'%d/%m/%Y') AS CRIACAO
				  FROM USER A
				  WHERE A.ID_EMP = '$pempresa'
				  AND A.USER <> '$puser'
				  AND USER_STATUS <> 'D'";



		if ($nome <> 'Todos') $query .= " AND A.USER =  '$nome'";

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$rquery = $envi->Executar($query,'');
	 // die(print_r($rquery));

		$i = 0;
		while ($row = mysqli_fetch_array($rquery)) {
			$qnome[$i] = $row['NOME'];
			$qcriador[$i] = $row['CRIADOR'];
			$qtipo[$i] = $row['TIPO'];
			$qstatus[$i] = $row['STATUS'];
			$qdtcriacao[$i] = $row['CRIACAO'];


			$dados[$i] = array('1' => $qnome[$i], '2' => $qcriador[$i], '3' => $qtipo[$i], '4' => $qstatus[$i], '5' => $qdtcriacao[$i]);
			$i++;

		}

		$coluna = array('1' => 'Usuário','2'=> 'Criador', '3'=>'Perfil', '4' =>'Status', '5' =>'Dt. Criação');
		$painel = new painel();
		$painel->estrutura(12,'Usuários',$coluna,$dados,'sim');
	}

}


if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT USER FROM USER
				WHERE ID_EMP = '$pempresa'
				AND USER = '$id'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$quser = $row['USER'];
	}

	
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->input('id','id',$id);
	$formulario->label('Nome do Usuário',2);
	$formulario->input('generico','usuario',$quser);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Editar',$_GET)){

	$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$dt = date('Y-m-d');

	$query = "UPDATE user
			   SET USER = '$usuario', 
			   CRIADOR = '$puser', 
			   DT_CRIACAO = '$dt'
			 WHERE ID_EMP = '$pempresa'
			 AND USER = '$id'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$user = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "UPDATE USER
			   SET USER_STATUS = 'D',
			   CRIADOR = '$puser'			       
			 WHERE user = '$user'
			 AND ID_EMP = '$pempresa'
			 ";
			 							// ESTADOS D DE DELETADO

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'Delete');	

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