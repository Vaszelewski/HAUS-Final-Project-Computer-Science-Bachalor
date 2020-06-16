var swiper;

function buscarCategorias(codCategoria){
	$.ajax({
		url: "ajax/categoria.php",
		method: "GET",
		data: {'parametros': '*', "categoriasUtilizadas": true},
		dataType: 'JSON',
		success: function(data){
			$('#listaCategoria option').remove();
			$('#listaCategoria').append('<option value="">Selecione uma categoria...</option>');

			if(data['dados'].length > 0)
			{
				let selected = "";

				$.each(data['dados'], function(chave, valor){
					selected = valor['cod_categoria'] == codCategoria ? "selected" : ""

					$('#listaCategoria').append('<option value="'+valor['cod_categoria']+'" '+selected+'>'+valor['nome']+'</option>');
				});
			}
		}
	});
}

function buscarColecoes(codCategoria){
	let dadosBusca = {
		'parametros': '*'
	}

	if(codCategoria != "")
	{
		dadosBusca['codCategoria'] = codCategoria;
	}

	$.ajax({
		url: "ajax/colecao.php",
		method: "GET",
		data: dadosBusca,
		dataType: 'JSON',
		success: function(data){
			$('.swiper-wrapper >').remove();

			if(data['resultado'] && data['dados'].length > 0)
			{
				$.each(data['dados'], function(chave, valor){
					let cardColecao = $('#templateColecao').clone();
					cardColecao.removeClass('none').removeAttr('id');
					cardColecao.find('h2').html(valor['titulo']);
					cardColecao.find('img').prop('src', valor['imagem']);

					cardColecao.find('a').click(function(){
						buscarDadosColecao(valor);
						contabilizarVisualizacao(valor['cod_colecao'], valor['cod_categoria']);
					});

					$('.swiper-wrapper').append(cardColecao);
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
		}
	});
}

function buscarDadosColecao(dadosColecao){
	$('#containerColecao').show();
	$('#containerColecoes').hide();

	$('#containerColecao').find('img').prop('src', dadosColecao['imagem']);
	$('#tituloColecao').html(dadosColecao['titulo']);
	$('#descricaoColecao p').html(dadosColecao['descricao']);

	$.ajax({
		url: "ajax/item.php",
		method: "GET",
		data: {'parametros': "*", 'codColecao': dadosColecao['cod_colecao']},
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

					$('#itensColecao').append(templateItem);
				});

				$('#itensColecao').lightGallery({
					selector: 'a'
				});
			}
		}
	});
}

function exibirColecoes(){
	$('#containerColecoes').show();
	$('#containerColecao').hide();
}

function contabilizarVisualizacao(codColecao, codCategoria){
	$.ajax({
		url: "ajax/colecao.php",
		method: "PATCH",
		data: {"codColecao": codColecao, "registrarVisualizacao": true},
		dataType: 'JSON'
	});

	$.ajax({
		url: "ajax/categoria.php",
		method: "PATCH",
		data: {"codCategoria": codCategoria},
		dataType: 'JSON'
	});
}

$(document).ready(function(){
	buscarColecoes(buscaCookie('codCategoria'));
	buscarCategorias(buscaCookie('codCategoria'));
	deletaCookie('codCategoria');

	$('#listaCategoria').change(function(){
		buscarColecoes($(this).val());
	});

	$('.setaVoltar').click(function(){
		exibirColecoes();
	});
});