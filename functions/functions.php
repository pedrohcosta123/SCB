<?php 
function removecaracteres($string){
	$vl = $string;

$vl = str_replace("-","","$vl");  // remove traço
$vl = str_replace("(","","$vl");  // remove traço
$vl = str_replace(")","","$vl");  // remove traço
$vl = str_replace(" ","","$vl");  // remove traço
$vl = str_replace(".","","$vl");  // remove traço
$vl = str_replace("/","","$vl");  // remove traço
return $vl;
}
function UrlAtual(){
	$dominio= $_SERVER['HTTP_HOST'];
	$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
	$res = explode("&", $url);
	return $res[0];

}

function url(){
	$dominio= $_SERVER['HTTP_HOST'];
	$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
	$res = explode("?", $url);
	return $res[0];

}

function carrega_menu($id_emp, $id_user,$menu){		

	$host= '';
	$bd= '';
	$senhabd= '; 
	$userbd = 'supri_pcosta'; 
	$link = mysqli_connect($host, $userbd, $senhabd, $bd);	
	$config = "SET NAMES 'utf8'; ";
	$query = "Select * from user where user = '$id_user' and id_emp = '$id_emp'";

	if (mysqli_connect_errno())
	{
		printf("Falha na conexão com o banco de dados: %s\n", mysqli_connect_error());
		exit();
	}

	if($dados = mysqli_query($link, $query)){ //while para buscar o nivel de acesso do usuário

		while ($row = mysqli_fetch_array($dados)) {
			$puser = $row["USER"];
			$ptipo = $row["TIPO"];		

		}
	}



	$query = "SELECT 
				ID_MENU,
				NOME_MENU
			 FROM MENU
				WHERE ID_MENU_PAI = 0
				ORDER BY NOME_MENU ASC";

	if (mysqli_connect_errno())
	{
		printf("Falha na conexão com o banco de dados: %s\n", mysqli_connect_error());
		exit();
	}

	$filho = ''; // declara variavel 
	$pai_menu = '';


	$dados = mysqli_query($link, $config);
   $contador = 0;
	if($dados = mysqli_query($link, $query)){

		while ($row = mysqli_fetch_array($dados)) {
			$nome_pai = $row['NOME_MENU'];
			$id_menu = $row['ID_MENU'];

			$pai_menu .= "<ul onclick='mudar($contador)' class='id'><li class='active'><a>$nome_pai</a></li><div id='$nome_pai' style='display:none'>";
			$contador++;

			$query1 = "SELECT ID_MENU, 
							NOME_MENU,
							ROTA_MENU 
						FROM MENU
							WHERE ID_MENU_PAI = '$id_menu'
							ORDER BY NOME_MENU ASC  ";

			$dados1 = mysqli_query($link,$query1);

			while ($row = mysqli_fetch_array($dados1)) {
				$menu_filho = $row['NOME_MENU'];
				$rota = $row['ROTA_MENU'];

				$pai_menu .= "<li><a href=$rota>$menu_filho</a></li>";
			}

			$pai_menu .="</div></ul>";
			
		}

// $filho .= "<li><a href=$prota_menu>$pnome_filho</a></li>";	
		// $pai_menu = "<ul class='active'><a>$nome_pai</a>$filho</ul>";

	}						

	$menu = $pai_menu;

	return $menu; // retorna menu
}
function carrega_topo($pempresa,$puser,$menu,$titulo,$icone){
	session_start();
	isset($_SESSION['USER']);
		$puser = $_SESSION['USER'];
		$pempresa = $_SESSION['ID_EMP'];
		$ptipo = $_SESSION['TIPO'];
	


	echo"	<!DOCTYPE html>
	<html>
	<head>
	<meta charset='utf-8'/>
	<meta name='viewport' content='width=device-width, initial-scale=1'/>
	<title>SCB</title>	
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js' type='text/javascript'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js'></script>	
	<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css'/>	
	<link rel='stylesheet' href='../bootstrap/css/bootstrap.min.css'>
	<link rel='stylesheet' href='../bootstrap/css/style.css'>	
	<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.eot'>
	<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.svg'>
	<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.ttf'>			
	<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.woff'>			
	<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.woff2'>		
	</head>

	<body>
	<nav class='navbar navbar-inverse'>
	<div class='container-fluid'>
	<div class='navbar-header'>
	<button type='button' class='navbar-toggle pull-left'>
	<span class='icon-bar'></span>
	<span class='icon-bar'></span>
	<span class='icon-bar'></span>
	</button>
	<a class='navbar-brand' href='http://suprinetit.com/SCB/login/home.php'>Home</a>
	</div>
	<div class='collapse navbar-collapse'>
	<ul class='nav navbar-nav navbar-right'>
	<li><a href='http://www.suprinetit.com/SCB/login/login.php'>Sair</a></li>
	</ul>
	</div>
	</div>
	</nav>
	<div class='main'>
	<div class='menu menu-open'>
	<ul>
	<li class='visible-xs'><a href='http://www.suprinetit.com/SCB/login/login.php'>Sair</a></li>";
	echo carrega_menu($pempresa,$puser,$menu);
	echo "
	</ul>
	</div>
	<div class='content' >
	<div class='col-md-12' align= center><h3>$titulo</h3></div>
	<div class='container-fluid well col-md-12'>";
}

function rodape(){

	echo "
	</div>

	</div>
	</div>
	<script>
		function mudar(posicao){
			var id = document.querySelectorAll('.id');
			
				 if(id[posicao].lastElementChild.style.display == 'block'){
				 	id[posicao].lastElementChild.style.display = 'none';
				 }
				 else{
				 	id[posicao].lastElementChild.style.display = 'block';
				 }

		};
	</script>
	<script>
		$(document).ready(function() {
			$('#table').DataTable();
		} );
	</script>
	<script type='text/javascript' src='../bootstrap/js/script.js'></script>
	<script type='text/javascript' src='../bootstrap/js/bootstrap.min.js'></script>
	<script src='https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js'></script>
	<script src='https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js'></script>
	<script src='https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src='https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'></script>
	<script type='text/javascript' src='//assets.locaweb.com.br/locastyle/2.0.6/javascripts/locastyle.js'></script>
	<script type='text/javascript' src='//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js'></script>	

	</body>

	</html>";
}
?>
