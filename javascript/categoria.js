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
			
			if(data['resultado'] && data['dados'].length > 0)
			{
				$.each(data['dados'], function(chave, valor){
					let cardColecao = $('#templateColecao').clone();
					cardColecao.removeClass('none').removeAttr('id');
					cardColecao.find('h2').html(valor['titulo']);
					cardColecao.find('img').prop('src', valor['imagem']);

					cardColecao.find('a').click(function(){
						buscarDadosColecao(valor);
					});

					$('.swiper-wrapper').append(cardColecao);
				});

				iniciarSwiper();
			}
			
		}
	});
}

function buscarDadosColecao(){

}

$(document).ready(function(){
	buscarColecoes(buscaCookie('codCategoria'));
	deletaCookie('codCategoria');
});