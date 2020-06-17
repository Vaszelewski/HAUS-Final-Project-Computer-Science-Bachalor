function cadastrarUsuario(){
	let dadosCadastro = {
		'nome': $('#nome').val().trim(),
		'sobrenome': $('#sobrenome').val().trim(),
		'email': $('#email').val().trim(),
		'senha': $('#senha').val().trim()
	}

	if(validaSenha(dadosCadastro['senha'], $('#confirmacao').val())){
		$.ajax({
			url: "ajax/usuario.php",
			method: "POST",
			data: dadosCadastro,
			success: function(data){
				if(data != '0'){
					exibeNotificacao('sucesso', 'Usuário cadastrado!')
				}else{
					exibeNotificacao('erro', 'E-mail já cadastrado.')
				}
			}
		});
	}else{
		exibeNotificacao('alerta', 'As senhas não coincidem.')
	}
}

function validaSenha(senha, confiramacao){
	return senha == confiramacao.trim();
}

$(document).ready(function(){
	$('#efetuarCadastro').click(function(){
		cadastrarUsuario();
	});
});