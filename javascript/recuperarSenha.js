function recuperarSenha(){
	let dadoEmail = {
		'email': $('#email').val().trim()
	}
	
	$.ajax({
		url: "ajax/envioEmailx.php",
		method: "POST",
		data: dadoEmail,
		success: function(data){
			if(data == 'true'){
				exibeNotificacao('sucesso', 'Solicitação Realizada!')
			}else{
				exibeNotificacao('erro', 'Email Inválido.')
			}
		}
	});
}

$(document).ready(function(){
	$('#recuperar').click(function(){
		recuperarSenha();
	});
});