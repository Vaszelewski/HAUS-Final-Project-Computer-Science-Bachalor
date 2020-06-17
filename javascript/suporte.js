function cadastrarSuporte(){
	let dadosSuporte = {
		'codOpcao': $('#opcao').val(),
		'email': $('#email').val().trim(),
		'assunto': $('#assunto').val().trim(),
		'mensagem': $('#mensagem').val().trim()
	}
	if(verificaCamposObrigatorios()){
		$.ajax({
			url: "ajax/suporte.php",
			method: "POST",
			data: dadosSuporte,
			dataType: 'JSON',
			success: function(data){
				if(data['resultado']){
					exibeNotificacao('sucesso', 'Mensagem enviada! Aguarde nosso retorno.')
				}else{
					exibeNotificacao('erro', data["log"])
				}
			}
		});
	}
}

function verificaCamposObrigatorios(){
	if($('#email').val().trim() == "")
	{
		exibeNotificacao('alerta', 'O email deve ser preenchido.');
		return false;
	}
	else if($('#opcao').val() == 0)
	{
		exibeNotificacao('alerta', 'Deve ser selecionado uma opção.');
		return false;
	}
	else if($('#assunto').val().trim() == "")
	{
		exibeNotificacao('alerta', 'O assunto deve ser preenchido.');
		return false;
	}
	else if($('#mensagem').val().trim() == "")
	{
		exibeNotificacao('alerta', 'A mensagem deve ser preenchida.');
		return false;
	}

	return true;
}

function buscaOpcoesSuporte(){
	$.ajax({
		url: "ajax/suporte.php",
		method: "GET",
		data: {'parametros': "cod_opcao, texto"},
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