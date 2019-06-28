<?php
	// classe para formulários
class form {



	function openform($tipo){
		echo"
		<form method='$tipo' id='form'>
		<div class='col-md-12 col-sm-12'>
		";
	}
	function opendiv($class,$alinhamento){
		if($class == ''){

			echo "<div class='well'>";
		}
		if($alinhamento == 1){

			echo "<div class = '$class' align= 'center'>";
		}
		if($alinhamento == 0){

			echo "<div class = '$class' >";
		}
	}
	function divid($id,$class){
		echo"<div id='$id' class='$class'>";
	}

	function closediv(){
		echo "
		</div>
		";

	}
	function radio($nome,$valor){

		echo "<a href='#' class='adicionarCampo ' title='Adicionar item'><img src='../login/teste.png' border='0' /></a>";
	}

	function label($nome,$tamanho){
		echo"
		<div class='col-sm-$tamanho col-md-$tamanho'>
		<label for='teste' class='control-label'>$nome:</label>";
	}

	function input($tipo, $nome, $valor) {

		switch ($tipo) {
			case 'data':
			echo "<input class='form-control date-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'cpf':
			echo "<input class='form-control cpf-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'cep':
			echo "<input class='form-control cep-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'time':
			echo "<input class='form-control time-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'datahora':
			echo "<input class='form-control date-time-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'fixo':
			echo "<input class='form-control phone-ddd-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'celular':
			echo "<input class='form-control cel-sp-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'date':
			echo "<input class='form-control date-mask' type='text' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'user':
			echo "<input class='form-control' maxlength='10' type= text name= $nome id= $nome value='$valor'><br>
			</div>";
			break;

			case 'password':
			echo "<input class='form-control' type= password maxlength='10' name='$nome' id='$nome' value= '$valor'><br>
			</div>";
			break;

			case 'decimal':
			echo "<input class='form-control phone-ddd-mask' type='text' name='$nome' id='$nome' data-mask='0.000.000.000,00' maxlength='16' value= $valor><br>
			</div>";
			break;

			case 'email':
			echo "<input class='form-control' type='email' name='$nome' id='$nome' value='$valor'><br>
			</div>";
			break;

			case 'numero':
			echo "<input class='form-control' type='text' name='$nome' id='$nome'  data-mask='0000000000' maxlength='10' value='$valor'><br>
			</div>";
			break;

			case 'id':
			echo "<input class='form-control' type='hidden' name='$nome' id='$nome'  maxlength='20' value='$valor'><br>
			</div>";
			break;

			case 'url':
			echo "<input class='form-control' type='url' name='$nome' id='$nome'  maxlength='200' value='$valor'><br>
			</div>";
			break;

			case 'generico':
			echo "<input class='form-control' type='text' name='$nome' id='$nome'  maxlength='60' value='$valor'><br>
			</div>";
			break;

			case 'cnpj':
			echo "<input class='form-control' type='text' name='$nome' id='$nome'  data-mask='00.000.800/0000-00' maxlength='18' value= $valor><br>
			</div>";
			break;	

			case 'desabilitado':
			echo "<input class='form-control' type='text' name='$nome' id='$nome'  maxlength='60' value='$valor' readonly><br>
			</div>";
			break;			

			case 'real':
			echo "<script >
			function moeda(z){
				v = z.value;
				v=v.replace(/\D/g,'') //permite digitar apenas números
				v=v.replace(/[0-9]{10}/,'inválido') //limita pra máximo 999.999.999,99
							// v=v.replace(/(\d{1})(\d{8})$/,'$1.$2') //coloca ponto antes dos últimos 8 digitos
							// v=v.replace(/(\d{1})(\d{1,2})$/,'$1.$2') //coloca ponto antes dos últimos 5 digitos
				v=v.replace(/(\d{1})(\d{1,2})$/,'$1,$2') //coloca virgula antes dos últimos 2 digitos
				z.value = v;
			}
			</script>
			<input class='valor form-control' type='text' name='$nome' maxlength='10' onKeyUp='moeda(this);'' value='$valor'><br>
			</div>";
			break;
		}
	}

	function selectestado($todos,$value){
		echo "<select name='UF' id ='UF' class='form-control'>";

		if($todos <> '') echo "<option value='Todos'>Todos</option>";

		if($value <> '')echo "<option value=$value>$value</option>";

		echo "<option value='AC'>AC</option>
		<option value='AL'>AL</option>
		<option value='AP'>AP</option>
		<option value='AM'>AM</option>
		<option value='BA'>BA</option>
		<option value='CE'>CE</option>
		<option value='DF'>DF</option>
		<option value='ES'>ES</option>
		<option value='GO'>GO</option>
		<option value='MA'>MA</option>
		<option value='MT'>MT</option>
		<option value='MS'>MS</option>
		<option value='MG'>MG</option>
		<option value='PA'>PA</option>
		<option value='PB'>PB</option>
		<option value='PR'>PR</option>
		<option value='PE'>PE</option>
		<option value='PI'>PI</option>
		<option value='RJ'>RJ</option>
		<option value='RN'>RN</option>
		<option value='RS'>RS</option>
		<option value='RO'>RO</option>
		<option value='RR'>RR</option>
		<option value='SC'>SC</option>
		<option value='SP'>SP</option>
		<option value='SE'>SE</option>
		<option value='TO'>TO</option>						  
		</select><br>
		</div>";
	}
	function select($nome,$vl){
		echo "<select name='$nome'class='form-control'>";

		foreach ($vl as $key => $value) {

			echo "<option value='$key'>$value</option>";
		}

		echo "</select><br></div>";
	}
	function selectt($nome,$vl){
		echo "<select name='$nome'onchange='alterar()'class='sele form-control' autofocus>";

		foreach ($vl as $key => $value) {

			echo "<option value='$key'>$value</option>";
		}

		echo "</select><br></div>";
	}

	function estadocivil($value){
		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$retorno = $envi->Executar("SELECT SIGLA, NOME FROM ESTADOCIVIL",'');

		while ($row = mysqli_fetch_array($retorno)) {
			$nome[$row['SIGLA']] = $row['NOME'];
		}

		echo "<select name='escivil'class='form-control'>";
		if($value <> ''){


			foreach ($nome as $key => $valr) {

				if($value == $key){
					echo"<option value=$key>$valr</option>";
				}
			}
		}

		foreach ($nome as $key => $vl) {
			if($value <> $key){
				echo"<option value=$key>$vl</option>";	
			}
		} 					  
		echo "</select><br>
		</div>";
	}

	function selectfunc($idemp,$todos){
		$sql =  new Conexao();
		$sql->Abrir();
		$envi = new Comando();
		$dados = $envi->Executar("select user from user where id_emp = '$idemp'",'');			
		$i = 0;
			// die(print_r($dados));

		while ($row = mysqli_fetch_array($dados)) {
			$user[$i] = $row["user"];	
			$i++;

		}

		echo "<select name='funci'class='form-control'>";

		foreach ($user as $key => $value) {

			echo "<option value='$value'> $value </option>";
		}

		if($todos == 'todos')echo "<option value='Todos'> Todos </option>";

		echo "			  					  
		</select><br>
		</div>";
	}

	function btn($class, $nome,$tamanho,$icone,$value) {

		if($value == 'jquery'){
			echo"<br>
			<button type='button' class='$class' name='$nome'>
			<i class='$icone'></i> $nome
			</button>
			";
			return;

		}

		switch ($class) {

			case 'azul':
			$class = 'btn btn-primary';
			break;

			case 'verde':
			$class = 'btn btn-success';
			break;

			case 'azulclaro':
			$class = 'btn btn-info';
			break;

			case 'laranja':
			$class = 'btn btn-warning';
			break;

			case 'vermelho':
			$class = 'btn btn-danger';
			break;
		}
		if($icone == ''){
			echo "	
			<input class='$class' type='submit' name='$nome' id='$nome'value='$value'>
			";
		}
		else{
			echo"
			<button type='submit' class='$class' name='$nome' id='$nome' value='$value'>
			<i class='$icone'></i> $nome
			</button>
			";
		}
	}

	function closeform(){
		echo "
		<br>
		<br>
		</div>
		</form>";
	}
}
class painel {

	function estrutura($tamanho,$title,$nomecoluna,$dados,$acao){
		echo " </div><div class='col-md-$tamanho'>
		<div class='panel panel-primary id='table'>
		<div class='panel-heading'>
		<h3 class='panel-title'>$title</h3>
		</div>
		<div class='panel-body'>
		<table class='table table-bordered col-md-$tamanho' id='table'>
		<thead>
		<tr>";

		$result = count($nomecoluna);
		$i = 1;

		while ( $i <= $result) {

			echo "<th>$nomecoluna[$i]</th>";

			$i++;
		}

		if($acao == 'sim'){
			echo "<th>Ação</th>";
		}

		echo "</tr>
		</thead>
		<tbody>";

		$url = url();
		// die($url);
		$cont = 1;
		if($dados <> ''){

			foreach ($dados as $key => $value) {
				$linha[$cont] = $key;
				$coluna[$cont] = $value;
				$cont++;
			}
			$con = 1;

			foreach ($linha as $key => $value) {

				echo "<tr>";
				foreach ($coluna[$con] as $key => $vl) {
					echo "<td> $vl </td>";
					if($key == 1){
						$resu = $vl;
					}
				}
				
				if($acao == 'sim'){
					echo "<td><a href='".$url.'?acao=edit&get='.$resu."'<span class='glyphicon glyphicon-pencil'</span></a> &nbsp
					<a href='".$url.'?delete=delete&get='.$resu."'<span class='glyphicon glyphicon-remove'></span></span></td>";
					echo "</tr>";
				}

				$con++;

			}
		}	
				// die(print_r($coluna));
		echo "	
		</tbody>
		</table>
		</div>
		</div>
		</div>
		";			

	}


}

	// classe para conexão no banco de dados

class Conexao{

	var $host    = "";
	var $usuario = "";
	var $senha = "@";
	var $banco = "";	
	var $mysqli;

	function Abrir(){	

		$this->mysqli = new mysqli($this->host, $this->usuario, $this->senha, $this->banco);
		$this->mysqli->query("SET NAMES 'utf8'"); 
	}

	function Fechar(){

		$this->mysqli->close();
	}
}

class Comando{

	public function Executar($sql,$tipo){
		$con = new Conexao();
		$url = $_SESSION['ROTA'];
		$con->Abrir();		
		$re = ''; 
		boolval($re);		



		if ($tipo == 'insert') {

			$re = $con->mysqli->query("SET NAMES 'utf8'");
			$re = $con->mysqli->query($sql);
			
			if($re == false ) {
				die($sql);
				header("Location:$url&insert=false");
			}

			else{

				$con->Fechar();
				header("Location:$url&insert=true");

			}
		}

		else{
			$dados = $con->mysqli->query("SET NAMES 'utf8'");
			$dados = $con->mysqli->query($sql);
				// die($sql);
			return $dados;

		}

	}
}

class alerta {

	public static function open($tipo,$titulo,$texto){

		switch ($tipo) {
			case 'verde':
			echo "</div>
			<div class= 'container-fluid col-md-12'>
			<div class='alert alert-success'>
			<strong>$titulo</strong> $texto
			</div>
			</div>";
			break;

			case 'azul':
			echo "</div>
			<div class= 'container-fluid col-md-12'><div class='alert alert-info'>
			<strong>$titulo</strong> $texto
			</div>
			</div>";
			break;

			case 'laranja':
			echo "</div>
			<div class= 'container-fluid col-md-12'><div class='alert alert-warning'>
			<strong>$titulo</strong> $texto
			</div>
			</div>";
			break;

			case 'vermelho':
			echo "
			</div>
			<div class= 'container-fluid col-md-12'>
			<div class='alert alert-danger'>
			<strong>$titulo</strong> $texto
			</div>
			</div>";
			break;
		}

	}
}

?>
