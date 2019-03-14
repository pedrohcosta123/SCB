<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Comissões a receber";
carrega_topo($pempresa,$puser,'Relatorios',$titulo,'glyphicon glyphicon-plus');

$res[0]= 'Todos';

$sql =  new Conexao();
$sql->Abrir();
$envi = new Comando();
$retorno = $envi->Executar("SELECT ID_SERV, NOME FROM CAD_TIPO_SERV WHERE ID_EMP = '$pempresa'AND STATUS = 'A' ORDER BY NOME",'');

while ($row = mysqli_fetch_array($retorno)) {
	$res[$row['ID_SERV']] = $row['NOME'];
}


$formulario = new form();
$formulario-> openform('GET');
$formulario->label('Tipo',3);	
$formulario->select('tpcomiss',$res);
$formulario->label('Data Inicial',3);
$formulario->input('data','dtinicial','');
$formulario->label('Data Final',3);
$formulario->input('data','dtfinal','');	
$formulario->label('Funcionário',3);
$formulario->selectfunc($pempresa,'todos');
$formulario->opendiv('col-sm-12 col-md-12','1');
$formulario->btn('azul','Consultar','12','glyphicon glyphicon-search','consul');
$formulario->closediv();
$formulario->closeform();

if(array_key_exists('menu',$_GET)){
	$acao = $_GET['menu'];
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->closeform();
}

if(array_key_exists('Consultar',$_GET)){
	$dtinicial = '';
	$dtfinal = '';

	$tpcomiss = $_GET['tpcomiss'];

	if ($_GET['dtinicial'] <> ''){
		$dtinicial = $_GET['dtinicial'];
		$array = explode('/', $dtinicial);
		$dtinicial = $array[2].'-'.$array[1].'-'.$array[0];
	}

	if ($_GET['dtfinal'] <> ''){
		$dtfinal = $_GET['dtfinal'];
		$array = explode('/', $dtfinal);
		$dtfinal = $array[2].'-'.$array[1].'-'.$array[0];
	}
	$funcionario = $_GET['funci']; 



	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$query = "SELECT A.ID_SERV AS ID,
			       B.ID_SERV,
			       B.NOME,
			       B.TIPO,
			       A.VL_SERV AS VALOR,
			       B.VL_SERV AS COMISSAO,
			       A.DT_INCLUSAO AS DATA,
			       A.USER 
			  FROM CAD_SERV A, CAD_TIPO_SERV B
			 WHERE A.ID_EMP = B.ID_EMP 
			 AND A.ID_EMP = '$pempresa'
			 AND A.ID_TIPO_SERV = B.ID_SERV	";

	if($dtinicial <> '') $query.= " AND A.DT_INCLUSAO >= '$dtinicial' ";

	if($dtfinal <> '') $query .= " AND A.DT_INCLUSAO <= '$dtfinal' ";

	if($funcionario <> 'Todos') $query .= " AND A.USER = '$funcionario' ";

	if($tpcomiss <> '0') $query .=" AND B.ID_SERV = '$tpcomiss' ";

	$return = $envi->Executar($query,'');
	$i = 0;

	while ($row = mysqli_fetch_array($return)) {
		$id[$i] = $row['ID'];
		$nome[$i] = $row['NOME'];
		if($row['TIPO'] == 'P'){

			$valor[$i] = $row['VALOR'];	
			$valor[$i] = $valor[$i]*$row['COMISSAO']/100;			
			$valor[$i] = number_format($valor[$i], 2, ',', '.');
		}
		else{
			$valor[$i] = $row['COMISSAO'];

			$valor[$i] = number_format($valor[$i], 2, ',', '.');
		} 
		$data[$i] = $row['DATA'];
		$user[$i] = $row['USER'];
		$dados[$i] = array('1' => $id[$i], '2' => $nome[$i], '3' =>$valor[$i],'4' =>$data[$i], '5' => $user[$i] );
		$i++;

	}
	$colunas = array('1' => 'ID','2'=> 'NOME', '3'=>'VALOR', '4' =>'Data', '5' => 'Cad. Por');
	$painel = new painel();
	$painel->estrutura(12,'Comissões a Receber',$colunas,$dados,'');
}

rodape();