function recuperarSenha(){
	let dadoEmail = {
		'email': $('#email').val().trim()
	}

	$.ajax({
		url: "ajax/envioEmailx.php",
		method: "POST",
		data: dadoEmail,
		success: function(data){
			if(data != '0'){
				exibeNotificacao('sucesso', 'Usuário cadastrado com sucesso!')
			}else{
				exibeNotificacao('erro', 'E-mail já cadastrado.')
			}
		}
	});
}

$(document).ready(function(){
	$('#recover').click(function(){
		recuperarSenha();
	});
});