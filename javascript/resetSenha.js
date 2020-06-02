function resetarSenha(){

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let dadoReset = {
		'chave' : urlParams.get('chave'),
		'email' : urlParams.get('email'),
		'acao' : urlParams.get('acao'),
		'senha': $('#senha').val().trim()
	}
	
	if(validaSenha(dadoReset['senha'], $('#senha2').val())){
		$.ajax({
			url: "ajax/resetSenhax.php",
			method: "POST",
			data: dadoReset,
			success: function(data){
				if(data == '1'){
					exibeNotificacao('sucesso', 'Senha alterada com sucesso!')
				}else{
					exibeNotificacao('erro', 'Este link é inválido ou expirou')
				}
			}
		});
	}else{
		exibeNotificacao('alerta', 'As senhas não coincidem.')
	}
}

function validaSenha(senha, senha2){
	return senha.trim() == senha2.trim();
}

$(document).ready(function(){
	$('#altera').click(function(){
		resetarSenha();
	});
});