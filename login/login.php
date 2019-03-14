<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/animate.css" rel="stylesheet">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/css/util.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/materialise/css/main.css">
  	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>  
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="padding: 20px 30px 20px 30px">
				<form class="login100-form validate-form" method="POST" id="formlogin">
					
					<span class="login100-form-title p-b-15">
						<img class="flip animated" src='scb logo.png' style="width:220px">
					</span>
					<div class="wrap-input100 validate-input" >
						<input class="input100" type="number" name="Empresa" id="empresa" data-validate="teste">
						<span class="focus-input100" data-placeholder="Digite a Empresa"></span>
					</div>

					<div class="wrap-input100 validate-input" >
						<input class="input100" type="text" name="User" id="user">
						<span class="focus-input100" data-placeholder="Digite o Usuário"></span>
					</div>

					<div class="wrap-input100 validate-input" style="margin-bottom: 10px">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="senha" id="senha">
						<span class="focus-input100" data-placeholder="Digite a Senha"></span>
					</div>

					<div class="container-login100-form-btn">
							<button class="btn btn-primary btn-block" type="submit">
								Entrar
							</button>
					</div>					
					<span "class="txt2">
						Esqueceu a senha?
					</span>

					<a class="txt2" href="#">
						click aqui!
					</a>						
					<div style="font-size:12px" class='shake animated  alert alert-danger' role='alert' id="errolog">
						<strong>Ops!</strong> Usuário ou senha incorretos, por favor tente novamente.
					</div>
				</form>
			</div>
		</div>
	</div>
	
	
<!--===============================================================================================-->
<script>

	$('#errolog').hide(); //Esconde o elemento com id errolog
	$(document).ready(function(){	
	$('#formlogin').submit(function(){ 	//Ao submeter formulário		
	$('#errolog').hide(); //Esconde o elemento com id errolog
		var empresa=$('#empresa').val();	//Pega valor do campo empresa
		var user=$('#user').val();	//Pega valor do campo usuario
		var senha=$('#senha').val();	//Pega valor do campo senha
		$.ajax({			//Função AJAX
			url:"verifica.php",			//Arquivo php
			type:"post",				//Método de envio
			data: "empresa="+empresa+"&user="+user+"&senha="+senha,	//Dados
				success: function (result){			//Sucesso no AJAX
	            		if(result==1){						
	            			location.href='home.php';	//Redireciona
	            		}
	            		if(result==0){
							$('#errolog').hide();
	            			$('#errolog').show();		//Informa o erro
	            		}
	        		}
		})
		return false;	//Evita que a página seja atualizada
	})
	})
</script>
	<!-- <script src="vendor/jquery/jquery-3.2.1.min.js"></script> -->
	<script src="../bootstrap/materialise/vendor/animsition/js/animsition.min.js"></script>
	<script src="../bootstrap/materialise/vendor/bootstrap/js/popper.js"></script>
	<script src="../bootstrap/materialise/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/materialise/vendor/select2/select2.min.js"></script>
	<script src="../bootstrap/materialise/vendor/daterangepicker/moment.min.js"></script>
	<script src="../bootstrap/materialise/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="../bootstrap/materialise/vendor/countdowntime/countdowntime.js"></script>
	<script src="../bootstrap/materialise/js/main.js"></script>
<!--===============================================================================================-->

</body>
</html>