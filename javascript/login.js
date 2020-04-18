function autenticarUsuario(){
	let dadosAutenticacao = {
		'email': $('#email').val().trim(),
		'senha': $('#senha').val().trim()
	}

	$.ajax({
		url: "ajax/usuario.php",
		method: "GET",
		data: dadosAutenticacao,
		success: function(data){
			if(data == 'true')
			{
				alert('Logado com sucesso!');
			}
			else
			{
				alert('Credenciais invalidas.')
			}
		}
	});
}

$(document).ready(function(){
	$('#entrar').click(function(){
		autenticarUsuario();
	});
});