function cadatrarUsuario(){
	let dadosCadastro = {
		'nome': $('#nome').val().trim(),
		'sobrenome': $('#sobrenome').val().trim(),
		'email': $('#email').val().trim(),
		'senha': $('#senha').val().trim()
	}

	if(validaSenha(dadosCadastro['senha'], $('#confirmacao').val()))
	{
		$.ajax({
			url: "../ajax/cadastro.php",
			method: "POST",
			data: dadosCadastro,
			sucess: function(data){
				console.log(data);
			}
		});
	}
	else
	{
		alert('As senhas não coincidem');
	}
}

function validaSenha(senha, confiramacao){
	return senha == confiramacao.trim();
}

function paginacaoCadastro(){
	$('#efetuarCadastro').click(function(){
		cadatrarUsuario()
	});
}