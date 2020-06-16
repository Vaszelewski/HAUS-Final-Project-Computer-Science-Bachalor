var swiper;

function buscaCategorias(Deferred){
	$.ajax({
		url: "ajax/categoria.php",
		method: "GET",
		data: {'parametros': 'nome, cod_categoria'},
		dataType: 'JSON',
		success: function(data){
			if(data['dados'].length > 0)
			{
				$('#listaCategoria option').remove();
				$('#listaCategoria').append('<option value="">Selecione uma categoria...</option>');
				$.each(data['dados'], function(chave, valor){
					$('#listaCategoria').append('<option value="'+valor['cod_categoria']+'">'+valor['nome']+'</option>');
				});
			}

			if(Deferred != undefined){
				Deferred.resolve();
			}
		}
	});
}

function cadastrarCategoria(){
	let cadastraCategoria = bootbox.confirm({
		title: "Cadastrar nova categoria",
		message: $('#formularioCategoria').html(),
		buttons: {
			confirm: {
				label: 'Cadastrar',
				className: 'btn btn-primary text-white'
			},
			cancel: {
				label: 'Cancelar'
			}
		},
		callback: function (result) {
			if(result)
			{
				let dadosCadastro = {
					'nome': $('.bootbox-body #nomeCategoria').val()
				}

				$.ajax({
					url: "ajax/categoria.php",
					method: "POST",
					data: dadosCadastro,
					dataType: 'JSON',
					success: function(data){
						if(data['resultado']){
							buscaCategorias();
							exibeNotificacao('sucesso', 'Categoria cadastrada com sucesso!');
							cadastraCategoria.modal('hide');
						}else{
							exibeNotificacao('erro', data['log']);
						}
					}
				});

				return false;
			}
		}
	});
}

function cadastrarColecao(dados){
	let formData = new FormData();

	$.each(dados['imagem'], function(chave, valor){
		formData.append(chave, valor);
	});

	delete dados['imagem'];

	$.each(dados, function(chave, valor){
		formData.append(chave, valor);
	});

	$.ajax({
		url: "ajax/colecao.php",
		method: "POST",
		data: formData,
		dataType: 'JSON',
		processData: false,
		contentType: false,
		success: function(data){
			buscaColecoes();
			exibirColecoes();
		}
	});
}

function buscaColecoes(){
	$.ajax({
		url: "ajax/colecao.php",
		method: "GET",
		data: {'parametros': '*', 'buscaColecoesUsuario': true},
		dataType: 'JSON',
		success: function(data){
			if(data['resultado'] && data['log'] == "")
			{
				$('.swiper-wrapper >').not(':first').remove();

				$.each(data['dados'], function(chave, valor){
					let templateColecao = $('#templateExibicaoColecao').clone();
					templateColecao.removeClass('none').removeAttr('id');
					templateColecao.find('.titulo').html(valor['titulo']);
					templateColecao.find('img').prop('src', valor['imagem']);

					templateColecao.find('.visualizar').click(function(){
						exibirColecao(valor);
					});

					templateColecao.find('.editar').click(function(){
						exibirAtualizacaoColecao('atualizar', valor);
					});

					templateColecao.find('.excluir').click(function(){
						excluirColecao(valor['cod_colecao'], templateColecao);
					});

					$('.swiper-wrapper').append(templateColecao);
				});

				if(swiper != undefined)
				{
					swiper.update();
				}
				else
				{
					swiper = iniciarSwiper();
				}
			}
			else
			{
				exibeNotificacao('erro', data['log']);
			}
		}
	});
}

function exibirColecoes(){
	$('#exibicaoColecoes').show();
	$('#cadastroAtualizacaoColecao').hide();
	$('#exibicaoColecao').hide();

	$('.formColecao .form-control').val('');
	$('.imagemPreview img').prop('src', 'imagens/imagemAdd.jpg');
	$('#arquivoImagem').val('');
}

function exibirColecao(colecao){
	$('#exibicaoColecao').show();
	$('#exibicaoColecoes').hide();

	$('#exibicaoColecao').find('img').prop('src', colecao['imagem']);
	$('#tituloColecao').html(colecao['titulo']);
	$('#descricaoColecao p').html(colecao['descricao']);

	carregarItensColecao(colecao['cod_colecao']);
}

function exibirAtualizacaoColecao(acao, colecao){
	$('#exibicaoColecoes').hide();
	$('#cadastroAtualizacaoColecao').show();

	var aguardarCategorias = $.Deferred();

	if(!($('#listaCategoria option').length > 1))
	{
		buscaCategorias(aguardarCategorias);
	}
	else
	{
		aguardarCategorias.resolve();
	}

	if(acao == 'atualizar')
	{
		$('#cadastrarColecao').hide();
		$('#atualizarColecao').show();
		$('#atualizarColecao').attr('codColecao', colecao['cod_colecao']);
		$('#cadastroAtualizacaoColecao h2').html('Atualizar coleção');

		$('#titulo').val(colecao['titulo']);
		$('#descricao').val(colecao['descricao']);

		$.when(aguardarCategorias).done(function(){
			$('#listaCategoria').val(colecao['cod_categoria'])
		});
		
		$('.imagemPreview img').prop('src', colecao['imagem']);
	}
	else
	{
		$('#cadastroAtualizacaoColecao h2').html('Cadastrar nova coleção');
		$('#cadastrarColecao').show();
		$('#atualizarColecao').hide();
	}
}

function excluirColecao(codColecao, elemento){
	bootbox.confirm({
		message: "Tem certeza que deseja excluir está coleção?",
		closeButton: false,
		centerVertical: true,
		buttons: {
			confirm: {
				label: 'Excluir',
				className: 'btn btn-primary text-white'
			},
			cancel: {
				label: 'Cancelar'
			}
		},
		callback: function (result) {
			if(result)
			{
				$.ajax({
					url: "ajax/colecao.php",
					method: "DELETE",
					data: {'codColecao': codColecao},
					dataType: 'JSON',
					success: function(data){
						if(data)
						{
							exibeNotificacao('sucesso', 'Coleção excluida com sucesso.');
							$(elemento).remove();
							swiper.update();
						}
						else
						{
							exibeNotificacao('erro', 'Falha na exclusão.');
						}
					}
				});
			}
		}
	});
}

function verificaCamposObrigatorios(){
	if($('#titulo').val().trim() == "")
	{
		exibeNotificacao('alerta', 'O título deve ser preenchido.');
		return false;
	}
	else if($('#listaCategoria').val() == "")
	{
		exibeNotificacao('alerta', 'Deve ser selecionado uma categoria.');
		return false;
	}
	else if($('#descricao').val().trim() == "")
	{
		exibeNotificacao('alerta', 'A descrição deve ser preenchida.');
		return false;
	}

	return true;
}

function atualizaColecao(dados){
	var aguardaAtualizacao = $.Deferred();

	$.ajax({
		url: "ajax/colecao.php",
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

				dados['atualizacaoCapa'] = true;

				$.each(dados, function(chave, valor){
					formData.append(chave, valor);
				});

				$.ajax({
					url: "ajax/colecao.php?",
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
		buscaColecoes();
		exibirColecoes();
	});
}


$(document).ready(function(){
	buscaColecoes();

	$('#exibirTelaCadastroColecao').click(function(){
		exibirAtualizacaoColecao('cadastrar');
	});

	$('.setaVoltar').click(function(){
		exibirColecoes();
	});

	$('.imagemPreview > div').click(function(){
		$('#arquivoImagem').click();
	});

	$('#arquivoImagem').change(function(){
		atualizaPreviewImagem(this.files[0], ".adicionarCapaColecao");
	});

	$('#cadastrarColecao').click(function(){
		let dadosCadastro = {
			'titulo': $('#titulo').val().trim(),
			'codCategoria': $('#listaCategoria').val(),
			'descricao': $('#descricao').val().trim(),
			'imagem': $('#arquivoImagem').prop('files')
		}

		if(verificaCamposObrigatorios())
		{
			cadastrarColecao(dadosCadastro);
		}
	});

	$('#atualizarColecao').click(function(){
		let dadosAtualizacao = {
			'titulo': $('#titulo').val().trim(),
			'codCategoria': $('#listaCategoria').val(),
			'descricao': $('#descricao').val().trim(),
			'codColecao': $('#atualizarColecao').attr('codColecao')
		}

		if(verificaCamposObrigatorios())
		{
			atualizaColecao(dadosAtualizacao);
		}
	});

	$('#cadastrarCategoria').click(function(){
		cadastrarCategoria();
	});

});