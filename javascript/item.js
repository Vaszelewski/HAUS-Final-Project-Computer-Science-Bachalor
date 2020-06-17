function carregarItensColecao(codColecao){
	$.ajax({
		url: "ajax/item.php",
		method: "GET",
		data: {'parametros': "*", 'codColecao': codColecao},
		dataType: 'JSON',
		success: function(data){
			$('#itensColecao').html("");

			if(data['resultado'] && data['dados'].length > 0)
			{
				if($('#itensColecao').data('lightGallery') != undefined)
				{
					$('#itensColecao').data('lightGallery').destroy(true);
				}

				$.each(data['dados'], function(chave, valor){
					let templateItem = $('#templateExibicaoItem >').clone();
					
					templateItem.find('a').attr('data-src', valor['imagem'])
					templateItem.find('a').attr('data-sub-html', "<h4>" + valor['titulo'] + "</h4>" + "<p>" + valor['descricao'] + "</p>")
					templateItem.find('img').prop('src', valor['imagem']);

					templateItem.find('.removeItem').click(function(){
						excluirItem(valor['cod_item'], codColecao);
					});

					$('#itensColecao').append(templateItem);
				});

				$('#itensColecao').lightGallery({
					selector: 'a'
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
								carregarItensColecao(codColecao);
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
			atualizaPreviewImagem(this.files[0], ".bootbox-body");
		});
	});
}

function excluirItem(codItem, codColecao){
	bootbox.confirm({
		message: "Tem certeza que deseja excluir este item?",
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
					url: "ajax/item.php",
					method: "DELETE",
					data: {'codItem': codItem, "codColecao": codColecao},
					dataType: 'JSON',
					success: function(data){
						if(data)
						{
							exibeNotificacao('sucesso', 'Item excluido.');
							carregarItensColecao(codColecao);
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

function itensReady(codColecao){
	$('#cadastrarItem').unbind().click(function(){
		cadastrarItem(codColecao);
	});
}