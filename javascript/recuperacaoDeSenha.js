function enviarEmail(dadoEmail){
	
	$.ajax({
		url: "ajax/recuperacaoDeSenha.php",
		method: "POST",
		data: dadoEmail,
		dataType: 'JSON',
		success: function(data){
			if(data){
				exibeNotificacao('sucesso', 'Solicitação Realizada!')
			}else{
				exibeNotificacao('erro', 'Email Inválido.')
			}
		}
	});
}

function resetarSenha(dadoReset){

	if(validaSenha(dadoReset['senha'], $('#senha2').val())){
		$.ajax({
			url: "ajax/recuperacaoDeSenha.php",
			method: "POST",
			data: dadoReset,
			dataType: 'JSON',
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
	let queryString = window.location.search;
	let urlParams = new URLSearchParams(queryString);
	let acao = urlParams.get('acao');

	if(acao == "enviarEmail"){
		$('#enviaEmail').show();
		$('#recuperaSenha').hide();
	} else if(acao == "alterarSenha"){
		$('#enviaEmail').hide();
		$('#recuperaSenha').show();
	}
	console.log(acao);

	$('#recuperar').click(function(){
		let dadoEmail = {
			'email': $('#email').val().trim(),
			'acao' : acao
		}

		enviarEmail(dadoEmail);
	});

	$('#altera').click(function(){
		let dadoReset = {
			'chave' : urlParams.get('chave'),
			'email' : urlParams.get('email'),
			'acao' : acao,
			'senha': $('#senha').val().trim()
		}

		resetarSenha(dadoReset);
	});
});