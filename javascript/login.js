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
				window.location.replace(window.location.origin + window.location.pathname);
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