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
			if(data == 'true'){
				exibeNotificacao('sucesso', 'Login efetuado com sucesso!')
			}else{
				exibeNotificacao('alerta', 'Credenciais Invalidas.')
			}
		}
	});
}

$(document).ready(function(){
	$('#entrar').click(function(){
		autenticarUsuario();
	});
});