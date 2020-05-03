function buscaDadosUsuario(){
	$.ajax({
		url: "ajax/usuario.php",
		method: "GET",
		data: {'parametros': 'nome, sobrenome, email, descricao, display_name'},
		dataType: 'JSON',
		success: function(data){
			$('#nome').val(data[0]['nome']);
			$('#sobrenome').val(data[0]['sobrenome']);
			$('#email').val(data[0]['email']);
			$('#displayName').val(data[0]['display_name']);
			$('#descricao').val(data[0]['descricao']);
		}
	});
}

function atualizarDadosUsuario(dados){
	var aguardaAtualizacao = $.Deferred();
	
	$.ajax({
		url: "ajax/usuario.php",
		method: "PATCH",
		data: dados,
		dataType: 'JSON',
		success: function(data){
			if(data && $('#arquivoImagem').prop('files')[0] != undefined)
			{
				let formData = new FormData();

				$.each($('#arquivoImagem').prop('files'), function(chave, valor){
					formData.append(chave, valor);
				});

				$.ajax({
					url: "ajax/usuario.php",
					method: "POST",
					data: formData,
					dataType: 'JSON',
					processData: false,
					contentType: false,
					success: function(data){
						aguardaAtualizacao.resolve();
					}
				});
			}
			else
			{
				aguardaAtualizacao.resolve();
			}
		}
	});

	$.when(aguardaAtualizacao).done(function(){
		window.location.replace(window.location.href);
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
			'displayName': $('#displayName').val().trim(),
			'descricao': $('#descricao').val().trim()
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
				exibeNotificacao("alerta", 'O arquivo "' + this.files[0]['name'] + '" não é uma imagem.');
			}
		}
	});

	$("#deslogar").click(function(){
		$.ajax({
			url: "ajax/usuario.php",
			method: "POST",
			data: {'deslogar': 'true'},
			dataType: 'JSON',
			success: function(data){
				window.location.replace(window.location.origin + window.location.pathname);
			}
		});
	});
});