<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Empresas";
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

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('Nome',6);
	$formulario->input('generico','name','');
	$formulario->label('Descrição',6);
	$formulario->input('generico','desc','');
	$formulario->label('CNPJ ou CPF',3);
	$formulario->input('cnpj','cpfcnpj','');
	$formulario->label('Email',6);
	$formulario->input('email','email','');
	$formulario->label('Telefone de Contato',3);
	$formulario->input('fixo','contato','');
	$formulario->label('Celular de Contato',3);
	$formulario->input('celular','celcontato','');
	$formulario->label('CEP',3);
	$formulario->input('cep','cep','');
	$formulario->label('Rua',6);
	$formulario->input('generico','rua','');
	$formulario->label('Bairro',6);
	$formulario->input('generico','bairro','');
	$formulario->label('Numero',2);
	$formulario->input('numero','numero','');
	$formulario->label('Cidade',4);
	$formulario->input('generico','cidade','');
	$formulario->label('UF',2);
	$formulario->selectestado('','');
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$nome = $_GET['name'];
	$desc = $_GET['desc'];
	$cpfcnpj = $_GET['cpfcnpj'];
	$cpfcnpj = removecaracteres($cpfcnpj);
	$email = $_GET['email'];
	$contato = $_GET['contato'];
	$contato = removecaracteres($contato);
	$celcontato = $_GET['celcontato'];
	$celcontato = removecaracteres($celcontato);
	$cep = $_GET['cep'];
	$cep = removecaracteres($cep);
	$rua = $_GET['rua'];
	$bairro = $_GET['bairro'];
	$numero = $_GET['numero'];
	$cidade = $_GET['cidade'];
	$uf = $_GET['UF'];
	$dtcriacao = date('Y-m-d');

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar("INSERT INTO `emp`(`NOME`, `DESCR`, `CNPJ_CPF`, `FONE_FIXO`, `FONE_CELULAR`, `EMAIL`, `CEP`, `RUA`, `BAIRRO`, `NUMERO`, `CIDADE`, `ESTADO`, `DT_CRIACAO`, `STATUS`, `user`) VALUES ('$nome','$desc','$cpfcnpj','$contato','$celcontato','$email','$cep','$rua','$bairro','$numero','$cidade','$uf','$dtcriacao','A','$puser')",'insert');



}
if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){
	$arstatus = array('A' => 'Ativo', 'I' => 'Inativo');

	$formulario = new form();
	$formulario-> openform('GET');	
	$formulario->label('ID',2);
	$formulario->input('numero','id','');
	$formulario->label('Nome',3);
	$formulario->input('generico','nome','');
	$formulario->label('CNPJ ou CPF',3);
	$formulario->input('cnpj','cpfcnpj','');	
	$formulario->label('Status',2);
	$formulario->select('Status',$arstatus);
	$formulario->opendiv('col-sm-12 col-md-12',1);
	$formulario->btn('azul','Consulta','12','glyphicon glyphicon-search','Consulta');
	$formulario->closediv();
	$formulario->closeform();

	if(array_key_exists('Consulta',$_GET) ){

		$idemp = isset($_GET['id']) ? $_GET['id'] : '';
		$nome = isset($_GET['nome']) ? $_GET['nome'] : '';
		$cnpj = isset($_GET['cpfcnpj']) ? $_GET['cpfcnpj'] : '';
		$cnpj = removecaracteres($cnpj);
		$status = isset($_GET['Status']) ? $_GET['Status'] : '';

		$query = "SELECT A.ID_EMP AS ID,
				       UPPER(A.NOME) AS NOME,
				       A.CNPJ_CPF AS CNPJ,
				       A.FONE_FIXO AS FIXO,
				       A.FONE_CELULAR AS CELULAR,
				       A.EMAIL AS EMAIL,
				       A.CIDADE AS CIDADE,
				       A.ESTADO AS ESTADO,
				       DATE_FORMAT(A.DT_CRIACAO,'%d/%m/%Y') AS DATA,
				       A.STATUS AS STATUS
				  FROM EMP A
				  WHERE 1 = 1 ";

		if($nome <> '') $query .= " AND A.NOME LIKE '%$nome%'";
		if($cnpj <> '') $query .= " AND A.CNPJ_CPF = '$cnpj'";
		if($status <> '')$query .= " AND A.STATUS = '$status'";
		if($idemp <> '') $query .= " AND A.ID_EMP = '$idemp'";

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$return = $envi->Executar($query,'');
	// die($query);

		$i = 0;
		while ($row = mysqli_fetch_array($return)) {
			$qidemp[$i] = $row['ID'];
			$qnome[$i] = $row['NOME'];
			$qcnpj[$i] = $row['CNPJ'];
			$qfixo[$i] = $row['FIXO'];
			$qcelular[$i] = $row['CELULAR'];
			$qemail[$i] = $row['EMAIL'];
			$qcidade[$i] = $row['CIDADE'];
			$qestado[$i] = $row['ESTADO'];
			$qdata[$i] = $row['DATA'];			
			$qstatus[$i] = $row['STATUS'];

			$dados[$i] = array('1' => $qidemp[$i], '2' => $qnome[$i], '3' =>$qcnpj[$i], '4' => $qfixo[$i], '5' => $qcelular[$i], '6' => $qemail[$i], '7' => $qcidade[$i], '8' => $qestado[$i], '9' => $qdata[$i], '10' => $qstatus[$i] );
			$i++;

		}
	// die(print_r($dados));
		$coluna = array('1' => 'ID','2'=> 'Nome', '3'=>'CNPJ', '4' =>'Tel. Fixo', '5' =>'Celular', '6' =>'E-mail', '7' =>'cidade', '8' =>'UF', '9' =>'Dt. Criacao', '10' =>'Status');
		$painel = new painel();
		$painel->estrutura(12,'Cadastro de Empresas',$coluna,$dados,'sim');
	}

}
if(array_key_exists('acao',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT ID_EMP,
			       NOME,
			       DESCR,
			       CNPJ_CPF,
			       FONE_FIXO,
			       FONE_CELULAR,
			       EMAIL,
			       CEP,
			       RUA,
			       BAIRRO,
			       NUMERO,
			       CIDADE,
			       ESTADO
			  FROM emp
			  WHERE ID_EMP = '$id'";


	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$qnome = $row['NOME'];
		$qdescr = $row['DESCR'];
		$qcpf = $row['CNPJ_CPF'];
		$qfixo = $row['FONE_FIXO'];
		$qcelular = $row['FONE_CELULAR'];
		$qemail = $row['EMAIL'];
		$qcep = $row['CEP'];
		$qrua = $row['RUA'];
		$qbairro = $row['BAIRRO'];
		$qnumero = $row['NUMERO'];
		$qcidade = $row['CIDADE'];
		$qestado = $row['ESTADO'];
	}

	
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$id);	
	$formulario->label('Nome',6);
	$formulario->input('generico','name',$qnome);
	$formulario->label('Descrição',6);
	$formulario->input('generico','desc',$qdescr);
	$formulario->label('CNPJ ou CPF',3);
	$formulario->input('cnpj','cpfcnpj',$qcpf);
	$formulario->label('Email',6);
	$formulario->input('email','email',$qemail);
	$formulario->label('Telefone de Contato',3);
	$formulario->input('fixo','contato',$qfixo);
	$formulario->label('Celular de Contato',3);
	$formulario->input('celular','celcontato',$qcelular);
	$formulario->label('CEP',3);
	$formulario->input('cep','cep',$qcep);
	$formulario->label('Rua',6);
	$formulario->input('generico','rua',$qrua);
	$formulario->label('Bairro',6);
	$formulario->input('generico','bairro',$qbairro);
	$formulario->label('Numero',2);
	$formulario->input('numero','numero',$qnumero);
	$formulario->label('Cidade',4);
	$formulario->input('generico','cidade',$qcidade);
	$formulario->label('UF',2);
	$formulario->selectestado('',$qestado);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}
if(array_key_exists('Editar',$_GET)){

	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$name = isset($_GET['name']) ? $_GET['name'] : '';
	$descr = isset($_GET['desc']) ? $_GET['desc'] : '';
	$cpf = isset($_GET['cpfcnpj']) ? $_GET['cpfcnpj'] : '';
	$cpf = removecaracteres($cpf);
	$email = isset($_GET['email']) ? $_GET['email'] : '';
	$contato = isset($_GET['contato']) ? $_GET['contato'] : '';
	$contato = removecaracteres($contato);
	$celcontato = isset($_GET['celcontato']) ? $_GET['celcontato'] : '';
	$celcontato = removecaracteres($celcontato);
	$cep = isset($_GET['cep']) ? $_GET['cep'] : '';
	$cep = removecaracteres($cep);
	$rua = isset($_GET['rua']) ? $_GET['rua'] : '';
	$bairro = isset($_GET['bairro']) ? $_GET['bairro'] : '';
	$numero = isset($_GET['numero']) ? $_GET['numero'] : '';
	$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
	$uf = isset($_GET['UF']) ? $_GET['UF'] : '';
	$dt = date('Y-m-d');

	$query = "UPDATE emp
			   SET NOME = '$name',
			       DESCR = '$descr',
			       CNPJ_CPF = '$cpf',
			       FONE_FIXO = 'contato',
			       FONE_CELULAR = '$celcontato',
			       EMAIL = '$email',
			       CEP = '$cep',
			       RUA = '$rua',
			       BAIRRO = '$bairro',
			       NUMERO = '$numero',
			       CIDADE = '$cidade',
			       ESTADO = '$uf',
			       DT_CRIACAO = '$dt',
			       user = '$puser'
			 WHERE ID_EMP = '$id'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$id = isset($_GET['get']) ? $_GET['get'] : '';	

	$query = "UPDATE EMP SET STATUS = 'I' WHERE ID_EMP = '$id'";
	// die($query);

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

rodape();