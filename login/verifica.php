<?php  

	include "../functions/functions.php";


	$empresa = $_POST['empresa'];
	$user=$_POST['user'];	//Pegando dados passados por AJAX
    $senha=$_POST['senha'];


	$host= 'robb0498.publiccloud.com.br:3306';
	$bd= 'suprinetit_SCB';
	$senhabd= 'Second123@'; 
	$userbd = 'supri_pcosta'; 
	$link = mysqli_connect($host, $userbd, $senhabd, $bd);
	$query = "Select * from user where id_emp ='$empresa' and user ='$user' and senha ='$senha'";

	if (mysqli_connect_errno())
	{
		printf("Falha na conexão com o banco de dados: %s\n", mysqli_connect_error());
		exit();
	}

	$dados = mysqli_query($link, $query) or die(mysqli_error());

	$res = mysqli_fetch_array($dados);

	if(@mysqli_num_rows($dados)== 0){
		echo 0;
	}
	else{
		echo 1; 
		if(!isset($_SESSION))

		session_start();

		$_SESSION['USER'] = $res['USER'];
		$_SESSION['ID_EMP'] = $res['ID_EMP'];
		$_SESSION['TIPO'] = $res['TIPO'];
		exit;
	}

	
?>

