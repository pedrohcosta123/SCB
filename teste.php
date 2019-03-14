<!DOCTYPE html>
<html>
<head>
	<title>teste</title>

	<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js' type='text/javascript'></script>
	<script type='text/javascript' src='https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js'></script>	
	<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css'/>	
	<meta name="charset=UTF-8" content="application/x-www-form-urlencoded">
</head>
<body>
	<input type="text" name="teste" id="endcep">



<script>

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
				alert('O processo número '+data['estado']+' foi enviado com sucesso');
			}
		});

	}
});
</script>

	<script type='text/javascript' src='//assets.locaweb.com.br/locastyle/2.0.6/javascripts/locastyle.js'></script>
	<script type='text/javascript' src='//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js'></script>	
</body>
</html>