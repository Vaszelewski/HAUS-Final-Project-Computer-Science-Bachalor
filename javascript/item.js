function carregarItensColecao(codColecao){
	$.ajax({
		url: "ajax/item.php",
		method: "GET",
		data: {'parametros': "*", 'codColecao': codColecao},
		dataType: 'JSON',
		success: function(data){
			if(data['resultado'] && data['dados'].length > 0)
			{
				$.each(data['dados'], function(chave, valor){
					let templateItem = $('#templateExibicaoItem >').clone();
					
					templateItem.attr('data-src', valor['imagem'])
					templateItem.attr('data-sub-html', valor['descricao'])
					templateItem.find('img').prop('src', valor['imagem']);

					$('#lightgallery').append(templateItem);
				});
			}
		}
	});

	itensReady(codColecao);
}

function cadastrarItem(codColecao){
	let modal = bootbox.confirm({
		title: "Cadastrar Novo Item",
		message: $("#formularioItens").html(),
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
				let dadosItem = {
					"codColecao": codColecao,
					"titulo": $('.bootbox-body #tituloItem').val().trim(),
					"descricao": $('.bootbox-body #descricaoItem').val().trim(),
					"imagem": $('#imagemItem').prop('files')
				};

				if(dadosItem['titulo'] == "")
				{
					exibeNotificacao('alerta', 'O título deve ser preenchido.');
					return false;
				}
				else if(dadosItem['descricao'] == "")
				{
					exibeNotificacao('alerta', 'A descrição deve ser preenchida.');
					return false;
				}
				else if($('#imagemItem').prop('files').length == 0)
				{
					exibeNotificacao('alerta', 'Deve ser adicionada uma imagem do item.');
					return false;
				}
				else
				{
					let formData = new FormData();

					$.each(dadosItem['imagem'], function(chave, valor){
						formData.append(chave, valor);
					});

					delete dadosItem['imagem'];

					$.each(dadosItem, function(chave, valor){
						formData.append(chave, valor);
					});

					$.ajax({
						url: "ajax/item.php",
						method: "POST",
						data: formData,
						dataType: 'JSON',
						processData: false,
						contentType: false,
						success: function(data){
							if(data['resultado'])
							{
								exibeNotificacao("sucesso", "Item cadastrado.");
							}
							else
							{
								exibeNotificacao("erro", data['log']);
							}
						}
					});
				}
			}
		}
	});

	modal.init(function(){
		$('.bootbox-body .imagemPreview').click(function(){
			$('#imagemItem').click();
		});

		$('#imagemItem').change(function(){
			console.log('teste');
			atualizaPreviewImagem(this.files[0], ".bootbox-body");
		});
	});
}

function itensReady(codColecao){
	$('#cadastrarItem').click(function(){
		cadastrarItem(codColecao);
	});
}