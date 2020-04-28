function buscaDadosUsuario(){
	$.ajax({
		url: "ajax/usuario.php",
		method: "GET",
		data: {'parametros': 'nome, sobrenome, email'},
		dataType: 'JSON',
		success: function(data){
			$('#nome').val(data[0]['nome']);
			$('#sobrenome').val(data[0]['sobrenome']);
			$('#email').val(data[0]['email']);
		}
	});
}

function atualizarDadosUsuario(dados){
	let formData = new FormData();

	$.each(dados['imagem'], function(chave, valor){
		formData.append(chave, valor)
	});
	delete dados['imagem'];
	
	$.each(dados, function(chave, valor){
		formData.append(chave, valor)
	});

	$.ajax({
		url: "ajax/usuario.php",
		method: "PATCH",
		data: dados,
		dataType: 'JSON',
		success: function(data){
			if(data)
			{
				exibeNotificacao('sucesso', 'Dados atualizados com sucesso!');
			}
		}
	});
}

function buscaImagemUsuario(){
	$.ajax({
		url: "ajax/usuario.php",
		method: "GET",
		data: {'imagemUsuario': 'true'},
		dataType: 'JSON',
		success: function(data){
			var image = new Image();
			image.src = data;
			$('#imagemPreview').prepend(image);
		}
	});
}

$(document).ready(function(){
	buscaDadosUsuario();
	buscaImagemUsuario();

	$('#atualizaPerfil').click(function(){
		let dados = {
			'nome': $('#nome').val().trim(),
			'sobrenome': $('#sobrenome').val().trim(),
			'email': $('#email').val().trim(),
			'imagem': $('#arquivoImagem').prop('files')[0]
		}

		atualizarDadosUsuario(dados);
	});

	$('#imagemPreview > div').click(function(){
		$('#arquivoImagem').click();
	});

	$('#arquivoImagem').change(function(){
		if(this.files[0])
		{
			if(this.files[0]['type'].indexOf("image/") == 0)
			{
				let reader = new FileReader();

				reader.onload = function(){
					$('#imagemPreview > img').remove();

					var image = new Image();
					image.src = reader.result;
					$('#imagemPreview').prepend(image);
				}

				reader.readAsDataURL(this.files[0]);
			}
			else
			{
				alert('O arquivo "' + this.files[0]['name'] + '" não é uma imagem.');
			}
		}
	});
});