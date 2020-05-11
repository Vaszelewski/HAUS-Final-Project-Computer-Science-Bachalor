function cadastrarSuporte(){
	let dadosSuporte = {
		'codOpcao': $('#opcao').val(),
		'codUsuario': $('#cod_usuario').val(),
		'email': $('#email').val().trim(),
		'assunto': $('#assunto').val().trim(),
		'mensagem': $('#mensagem').val().trim()
	}

	$.ajax({
		url: "ajax/suporte.php",
		method: "POST",
		data: dadosSuporte,
		success: function(data){
			if(data != '0'){
				exibeNotificacao('sucesso', 'Mensagem enviada com Sucesso! Aguarde nosso retorno em breve.')
			}else{
				exibeNotificacao('erro', 'Algum campo obrigatório não foi preenchido! Favor verifique.')
			}
		}
	});
}

function buscaOpcoesSuporte(){
	$.ajax({
		url: "ajax/suporte.php?parametros=cod_opcao,texto",
		method: "GET",
		dataType: 'JSON',
		success: function(data){
			$.each(data, function(chave, valor){
				$('#opcao').append('<option value="'+valor["cod_opcao"]+'">'+valor["texto"]+'</option>');
			});
		}
	});
}



$(document).ready(function(){
	buscaOpcoesSuporte();

	$('#acionarSuporte').click(function(){
		cadastrarSuporte();
	});
});