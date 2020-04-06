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
			url: "../ajax/usuario.php",
			method: "POST",
			data: dadosCadastro,
			success: function(data){
				if(data != '0')
				{
					alert('Usuário cadastrado com sucesso!');
				}
				else
				{
					alert('E-mail já cadastrado.');
				}
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