<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Home</title>
		<meta charset='utf-8'>
		<link rel='stylesheet' href='../bootstrap/css/bootstrap.min.css'>
		<link rel='stylesheet' href='../bootstrap/css/style.css'>
		<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.eot'>
		<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.svg'>
		<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.ttf'>			
		<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.woff'>			
		<link rel='stylesheet' href='../bootstrap/fonts/glyphicons-halflings-regular.woff2'>
</head>

<body>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle pull-left">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="http://suprinetit.com/SCB/login/home.php">Home</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="http://suprinetit.com/SCB/login/login.php">Sair</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="main">
		<div class="menu menu-open">
			<ul>
				<li class="visible-xs"><a href="http://suprinetit.com/SCB/login/login.php">Sair</a></li>
				<?php 

					include("../functions/functions.php");
					session_start();
					if (isset($_SESSION['USER'])){
						$puser = $_SESSION['USER'] ;
						$pempresa = $_SESSION['ID_EMP'];
						$ptipo = $_SESSION['TIPO'];
					}
					else{

						session_destroy();
						header("Location:http://suprinetit.com/SCB/login/login.php");
					}
					echo carrega_menu($pempresa,$puser,'');
				?>
			</ul>
		</div>
		<div class="content">
		<!-- onde serão apresentados os formulários -->
			<div class="col-sm-6 text-center" >
					<?php
						if(array_key_exists('funcao',$_GET)){

							$form = $_GET['funcao']; 
							carrega_form($form);
						}

					?>
			</div>

		</div>
	</div>
	<script>
		function mudar(posicao){
			var id = document.querySelectorAll(".id");
			
				 if(id[posicao].lastElementChild.style.display == 'block'){
				 	id[posicao].lastElementChild.style.display = 'none';
				 }
				 else{
				 	id[posicao].lastElementChild.style.display = 'block';
				 }

		};
	</script>
	<script type="text/javascript" src="../bootstrap/js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="../bootstrap/js/script.js"></script>
</body>

</html>