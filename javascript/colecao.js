function buscaCategorias(){
	$.ajax({
		url: "ajax/categoria.php",
		method: "GET",
		data: {'parametros': 'nome, cod_categoria'},
		dataType: 'JSON',
		success: function(data){
			if(data['dados'].length > 0)
			{
				$.each(data['dados'], function(chave, valor){
					$('#listaCategoria').append('<option value="'+valor['cod_categoria']+'">'+valor['nome']+'</option>');
				});
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

	console.log(JSON.stringify(formData));

	$.ajax({
		url: "ajax/colecao.php",
		method: "POST",
		data: formData,
		dataType: 'JSON',
		processData: false,
		contentType: false,
		success: function(data){
			console.log(data);
		}
	});
}

function buscaColecoes(){
	let propriedadesSwiper = {
		pagination: {
			el: '.swiper-pagination'
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev'
		},
		mousewheel: {
			invert: false,
			forceToAxis: true,
			releaseOnEdges: true
		},
		freeMode: true,
		spaceBetween: 30,
		mousewheel: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true
		},
		slidesPerView: 3,
		breakpoints: {
			668: {
				slidesPerView: 1
			},
			1024: {
				slidesPerView: 2 
			}
		},
		spaceBetween: 20
	};

	let swiper = new Swiper ('.swiper-container', propriedadesSwiper);

	$.ajax({
		url: "ajax/colecao.php",
		method: "GET",
		data: {'parametros': '*', 'buscaColecoesUsuario': true},
		dataType: 'JSON',
		success: function(data){
			$.each(data, function(chave, valor){
				let exibicaoColecao = $('#templateExibicaoColecao').clone();
				exibicaoColecao.removeClass('none');
				exibicaoColecao.find('.titulo').html(valor['nome']);
				exibicaoColecao.find('a').prop('id', valor['cod_colecao']);
				exibicaoColecao.find('img').prop('src', valor['imagem']);

				let html = '\
					<div class="swiper-slide" style="width: 432px; margin-right: 20px;">\
						<div class="image-wrap">\
							<div class="image-info">\
								<h2 class="mb-3">'+valor['nome']+'</h2>\
								<a id="'+valor['cod_colecao']+'" class="btn btn-outline-white py-2 px-4">More Photos</a>\
							</div>\
							<img src="'+valor['imagem']+'" alt="Image">\
						</div>\
					</div>';

				swiper.appendSlide(html);
			});
		}
	});
}

$(document).ready(function(){
	buscaColecoes();

	$('#exibirTelaCadastroColecao').click(function(){
		$('#exibicaoColecoes').hide();
		$('#cadastroNovaColecao').show();

		if(!($('#listaCategoria option').length > 1))
		{
			buscaCategorias();
		}
	});

	$('#exibirColecao').click(function(){
		$('#exibicaoColecoes').show();
		$('#cadastroNovaColecao').hide();

		$('#formColecao .form-control').val('');
		$('#imagemPreview img').prop('src', 'imagens/imagemAdd.jpg')
		$('#arquivoImagem').val('')
	});

	$('#imagemPreview > div').click(function(){
		$('#arquivoImagem').click();
	});

	$('#arquivoImagem').change(function(){
		atualizaPreviewImagem(this.files[0]);
	});

	$('#cadastrarColecao').click(function(){
		let dadosCadastro = {
			'titulo': $('#titulo').val(),
			'codCategoria': $('#listaCategoria').val(),
			'descricao': $('#descricao').val(),
			'imagem': $('#arquivoImagem').prop('files')
		}

		cadastrarColecao(dadosCadastro);
	});
});