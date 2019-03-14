<?php  

include("../functions/functions.php");
include("../functions/classe.php");
session_start();
$puser = $_SESSION['USER'] ;
$pempresa = $_SESSION['ID_EMP'];
$ptipo = $_SESSION['TIPO'];
$titulo = "Cadastro de Cliente";
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
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('Nome',5);
	$formulario->input('generico','namecli','');	
	$formulario->label('CPF',3);
	$formulario->input('cpf','cpfcli','');
	$formulario->label('Data de Nascimento',2);
	$formulario->input('date','dtnasc','');
	$formulario->label('Estado Civil',2);
	$formulario->estadocivil('');
	$formulario->label('CEP',2);
	$formulario->input('cep','endcep','');
	$formulario->label('Rua',4);
	$formulario->input('generico','namerua','');
	$formulario->label('Bairro',4);
	$formulario->input('generico','namebairro','');
	$formulario->label('Numero',1);
	$formulario->input('numero','numero','');
	$formulario->label('Complemento',4);
	$formulario->input('generico','complemento','');
	$formulario->label('Cidade',3);
	$formulario->input('generico','cidade','');
	$formulario->label('Estado',2);
	$formulario->selectestado('','');
	$formulario->label('Telefone Fixo',3);
	$formulario->input('fixo','telfixo','');
	$formulario->label('Celular 1',3);
	$formulario->input('celular','cel1','');
	$formulario->label('Celular 2',3);
	$formulario->input('celular','cel2','');
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Salvar','12','glyphicon glyphicon-plus','salvar');
	$formulario->closediv();
	$formulario->closeform();
}

if(array_key_exists('Salvar',$_GET)){

	$nome = $_GET['namecli'];
	$cpf = $_GET['cpfcli'];
	$cpf = str_replace(".","","$cpf");	// remove pontos	
	$cpf = str_replace("-","","$cpf");  // remove traço
	$dtnasc = $_GET['dtnasc'];
	$dtnasc = str_replace("/","-","$dtnasc");  //troca barra por traço
	$array = explode('-', $dtnasc);
	$date = $array[2].'-'.$array[1].'-'.$array[0];
	$escivil = $_GET['escivil'];
	$endcep = $_GET['endcep'];
	$endcep = str_replace("-","","$endcep");  // remove traço
	$namerua = $_GET['namerua'];
	$namebairro = $_GET['namebairro'];
	$numero = $_GET['numero'];
	$complemento = $_GET['complemento'];
	$cidade = $_GET['cidade'];
	$uf = $_GET['UF'];
	$telfixo = $_GET['telfixo'];	
	$telfixo = removecaracteres($telfixo);
	$cel1 = $_GET['cel1'];
	$cel1 = removecaracteres($cel1)	;	
	$cel2 = $_GET['cel2'];	
	$cel2 = removecaracteres($cel2);
	$dtcriacao = date('Y-m-d');
	
	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$select = "INSERT INTO `cad_clientes`(`ID_EMP`, `CPF`, `NOME`, `DT_NASC`, `EST_CIVIL`, `CEP`, `RUA`, `BAIRRO`, `NUMERO`, `COMPLEMENTO`, `CIDADE`, `ESTADO`, `TELEFONE1`, `TELEFONE2`, `TELEFONE3`, `LOGIN`, `DT_CADASTRO`) VALUES ('$pempresa','$cpf','$nome','$date','$escivil','$endcep','$namerua','$namebairro','$numero','$complemento','$cidade','$uf','$telfixo','$cel1','$cel2','$puser','$dtcriacao')";

	$envi->Executar($select,'insert');	
}


if(array_key_exists('Consultar',$_GET) or array_key_exists('Consulta',$_GET)){
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('Data Inicial',2);
	$formulario->input('date','dtinicial','');
	$formulario->label('Data Final',2);
	$formulario->input('date','dtfinal','');
	$formulario->label('Nome',5);
	$formulario->input('generico','namecli','');	
	$formulario->label('CPF',3);
	$formulario->input('cpf','cpfcli','');
	$formulario->label('Data de Nascimento',2);
	$formulario->input('date','dtnasc','');
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('azul','Consulta','3','glyphicon glyphicon-search','consul');
	$formulario->closediv();
	$formulario->closeform();



	if(array_key_exists('Consulta',$_GET) ){
		
		$dtinicial = isset($_GET['dtinicial']) ? $_GET['dtinicial'] : '';
		$dtfinal = isset($_GET['dtfinal']) ? $_GET['dtfinal'] : '';
		$namecli = isset($_GET['namecli']) ? $_GET['namecli'] : '';
		$cpf = isset($_GET['cpfcli']) ? $_GET['cpfcli'] : '';
		$cpf = removecaracteres($cpf);
		$dtnasc = isset($_GET['dtnasc']) ? $_GET['dtnasc'] : '';


		$query = "SELECT ID,
		ID_EMP AS EMPRESA,
		CPF,
		NOME,
		TELEFONE1 AS FIXO,
		TELEFONE2 AS CELULAR,
		LOGIN,
		DATE_FORMAT(DT_CADASTRO, '%d/%m/%Y') AS DATA,
		LOGIN
		FROM CAD_CLIENTES
		WHERE ID_EMP = '$pempresa'";

		if($cpf <> '') $query .= "AND CPF LIKE '%$cpf%'";

		if($dtinicial <> '') $query .= "AND DATE_FORMAT(DT_CADASTRO, '%d/%m/%Y') >= '$dtinicial'";

		if($dtfinal <> '') $query .= "AND DATE_FORMAT(DT_CADASTRO, '%d/%m/%Y') <= '$dtfinal'";

		if($namecli <> '') $query .= "AND NOME LIKE '%$namecli%' ";

		if($dtnasc <> '') $query .= "AND DATE_FORMAT(DT_NASC, '%d/%m/%Y') = '$dtnasc'";

		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$return = $envi->Executar($query,'');
		// die($query);

		$i = 0;
		while ($row = mysqli_fetch_array($return)) {
			$qcpf[$i] = $row['CPF'];
			$nome[$i] = $row['NOME'];
			$fixo[$i] = $row['FIXO'];
			$celular[$i] = $row['CELULAR'];
			$login[$i] = $row['LOGIN'];
			$dtcadastro[$i] = $row['DATA'];
			$dados[$i] = array('1' => $qcpf[$i], '2' => $nome[$i], '3' =>$fixo[$i], '4' => $celular[$i], '5' => $login[$i], '6' => $dtcadastro[$i] );
			$i++;

		}
		// die(print_r($dados));
		$coluna = array('1' => 'CPF','2'=> 'Nome', '3'=>'Tel. Fixo', '4' =>'Celular', '5' =>'Login', '6' =>'Dt. Cadastro');
		$painel = new painel();
		$painel->estrutura(12,'Clientes Cadastrados',$coluna,$dados,'sim');
	}

}
if(array_key_exists('acao',$_GET)){

	$cpf = isset($_GET['get']) ? $_GET['get'] : '';

	$query = "SELECT ID,
	CPF,
	NOME,
	DATE_FORMAT(DT_NASC,'%d/%m/%Y') AS DATA,
	EST_CIVIL AS CIVIL,
	CEP,
	RUA,
	BAIRRO,
	NUMERO,
	COMPLEMENTO,
	CIDADE,
	ESTADO,
	TELEFONE1,
	TELEFONE2,
	TELEFONE3
	FROM cad_clientes
	WHERE ID_EMP = '$pempresa' 
	AND CPF = '$cpf'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$return = $envi->Executar($query,'');

	while ($row = mysqli_fetch_array($return)) {
		$qid = $row['ID'];
		$qcpf = $row['CPF'];
		$qnome = $row['NOME'];
		$qdtnasc = $row['DATA'];
		$qcivil = $row['CIVIL'];
		$qcep = $row['CEP'];
		$qrua = $row['RUA'];
		$qbairro = $row['BAIRRO'];
		$qnumero = $row['NUMERO'];
		$qcomplemento = $row['COMPLEMENTO'];
		$qcidade = $row['CIDADE'];
		$qestado = $row['ESTADO'];
		$qtelefone1 = $row['TELEFONE1'];
		$qtelefone2 = $row['TELEFONE2'];
		$qtelefone3 = $row['TELEFONE3'];
	}	
	// die(print_r($row));
	$formulario = new form();
	$formulario-> openform('GET');
	$formulario->label('ID',2);
	$formulario->input('desabilitado','id',$qid);
	$formulario->label('Nome',5);
	$formulario->input('generico','namecli',$qnome);	
	$formulario->label('CPF',3);
	$formulario->input('cpf','cpfcli',$qcpf);
	$formulario->label('Data de Nascimento',2);
	$formulario->input('date','dtnasc',$qdtnasc);
	$formulario->label('Estado Civil',2);
	$formulario->estadocivil($qcivil);
	$formulario->label('CEP',2);
	$formulario->input('cep','endcep',$qcep);
	$formulario->label('Rua',4);
	$formulario->input('generico','namerua',$qrua);
	$formulario->label('Bairro',4);
	$formulario->input('generico','namebairro',$qbairro);
	$formulario->label('Numero',1);
	$formulario->input('numero','numero',$qnumero);
	$formulario->label('Complemento',4);
	$formulario->input('generico','complemento',$qcomplemento);
	$formulario->label('Cidade',3);
	$formulario->input('generico','cidade',$qcidade);
	$formulario->label('Estado',2);
	$formulario->selectestado('',$qestado);
	$formulario->label('Telefone Fixo',3);
	$formulario->input('fixo','telfixo',$qtelefone1);
	$formulario->label('Celular 1',3);
	$formulario->input('celular','cel1',$qtelefone2);
	$formulario->label('Celular 2',3);
	$formulario->input('celular','cel2',$qtelefone3);
	$formulario->opendiv('col-sm-3 col-md-12',1);
	$formulario->btn('verde','Editar','12','glyphicon glyphicon-penci','Salvar');
	$formulario->closediv();
	$formulario->closeform();
}
if(array_key_exists('Editar',$_GET)){
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$namecli = isset($_GET['namecli']) ? $_GET['namecli'] : '';
	$cpfcli = isset($_GET['cpfcli']) ? $_GET['cpfcli'] : '';
	$cpfcli = removecaracteres($cpfcli);
	$dtnasc = isset($_GET['dtnasc']) ? $_GET['dtnasc'] : '';
	$dtnasc = str_replace("/","-","$dtnasc");  //troca barra por traço
	$array = explode('-', $dtnasc);
	$date = $array[2].'-'.$array[1].'-'.$array[0];
	$escivil = isset($_GET['escivil']) ? $_GET['escivil'] : '';
	$endcep = isset($_GET['endcep']) ? $_GET['endcep'] : '';
	$endcep = removecaracteres($endcep);
	$namerua = isset($_GET['namerua']) ? $_GET['namerua'] : '';
	$namebairo = isset($_GET['namebairo']) ? $_GET['namebairro'] : '';
	$numero = isset($_GET['numero']) ? $_GET['numero'] : '';
	$complemento = isset($_GET['complemento']) ? $_GET['complemento'] : '';
	$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
	$uf = isset($_GET['UF']) ? $_GET['UF'] : '';
	$telfixo = isset($_GET['telfixo']) ? $_GET['telfixo'] : '';
	$telfixo = removecaracteres($telfixo);
	$cel1 = isset($_GET['cel1']) ? $_GET['cel1'] : '';
	$cel1 = removecaracteres($cel1);
	$cel2 = isset($_GET['cel2']) ? $_GET['cel2'] : '';
	$cel2 = removecaracteres($cel2);
	$data = date('Y-m-d');

	$query = "UPDATE `cad_clientes`
			SET `CPF` = '$cpfcli',
			`NOME` = '$namecli',
			`DT_NASC` = '$date',
			`EST_CIVIL` = '$escivil',
			`CEP` = '$endcep',
			`RUA` = '$namerua',
			`BAIRRO` = '$namebairo',
			`NUMERO` = '$numero',
			`COMPLEMENTO` = '$complemento',
			`CIDADE` = '$cidade',
			`ESTADO` = '$uf',
			`TELEFONE1` = '$telfixo',
			`TELEFONE2` = '$cel1',
			`TELEFONE3` = '$cel2',
			`LOGIN` = '$puser',
			`DT_CADASTRO` = '$data'
			WHERE ID = $id";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$envi->Executar($query,'insert');	
}

if(array_key_exists('delete',$_GET)){

	$cpf = isset($_GET['get']) ? $_GET['get'] : '';

	
	$query1 = "SELECT * 
			FROM CAD_SERV
			WHERE ID_EMP = '$pempresa'
			AND CPF_CLI = '$cpf'";

	$sql =  new Conexao();
	$sql->Abrir();
	$envi = new Comando();
	$dados1 = $envi->Executar($query1,'consul');

	if(mysqli_num_rows($dados1)>0){
		$formulario = new form();
		$formulario-> openform('GET');
		$formulario->opendiv('col-sm-12 col-md-12',1);
		$formulario->btn('laranja','Voltar','1','glyphicon glyphicon-arrow-left','return');
		$formulario->closediv();
		$formulario->closeform();
		alerta::open('vermelho','Ops!! Não foi possível deletar este dados :( ','<br>Existem serviços cadastrados para esse cliente, por favor verificar!');
	}
	else{

		$query2 = "DELETE 
				FROM CAD_CLIENTES 
				WHERE ID_EMP = '$pempresa' 
				AND CPF = '$cpf'";
				
		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$envi->Executar($query2,'insert');
	}

}
if(array_key_exists('Voltar',$_GET)){

	$url = $_SESSION['ROTA'];

	header("Location:$url");
}


echo "

<script>
$('#cidade').attr('readonly', true);
$('#UF').attr('readonly', true);
$('#UF').attr('style', 'pointer-events: none;');

$('#endcep').keyup(function() {
	var tamanho = $('#endcep').val().length;
	var valor = $('#endcep').val();
	if(tamanho == 9){

		$.ajax({
			type: 'POST',
			url: 'https://api.postmon.com.br/v1/cep/'+valor,
			contentType: 'application/json',
			dataType:'jsonp',
			responseType:'application/json',
			xhrFields: {
				withCredentials: false
			},
			
		  headers: {
		    'Access-Control-Allow-Credentials' : true,
		    'Access-Control-Allow-Origin':'*',
		    'Access-Control-Allow-Methods':'GET',
		    'Access-Control-Allow-Headers':'application/json',
		  },

			success: function(data) {
				$('#namerua').val(data['logradouro']);
				$('#namebairro').val(data['bairro']);
				$('#cidade').val(data['cidade']);				
				$('#UF').val(data['estado']);
			},

			error: function(data){
				alert('cep não encontrado');		
			}
		});

	}
});

$('#Salvar').click(function(e){


	$( '#novadiv' ).remove();	
			
	    
	var tamanho = $('#endcep').val().length;
	if(tamanho < 9){
		alert('Cep inválido');

		$('#form').submit(function(event){
	   		event.preventDefault();
	   	});
	}
	if(tamanho == 9){

		var valor = $('#endcep').val();	


		$.ajax({
				type: 'POST',
				url: 'https://api.postmon.com.br/v1/cep/'+valor,
				contentType: 'application/json',
				dataType:'jsonp',
				responseType:'application/json',
				xhrFields: {
					withCredentials: false
				},
				
			  headers: {
			    'Access-Control-Allow-Credentials' : true,
			    'Access-Control-Allow-Origin':'*',
			    'Access-Control-Allow-Methods':'GET',
			    'Access-Control-Allow-Headers':'application/json',
			  },

				success: function(data) {
					$('#cidade').val(data['cidade']);				
					$('#UF').val(data['estado']);

					
					
				},

				error: function(data){
					if(data['status'] == '404'){
				    	alert('CEP inválido');
				    	$('#form' ).append( '<div id=novadiv></div>' );
				    }	
				}

				
		});

		
	if(document.getElementById('novadiv') == null){

		alert('entrou');
		return false;
	}
	else{
		alert('se não');
		return true;
	}
	}

});
</script>



";

rodape();
