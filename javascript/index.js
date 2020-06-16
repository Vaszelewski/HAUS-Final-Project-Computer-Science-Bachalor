function carregarColecoesPublicas(){
	$.ajax({
		url: "ajax/categoria.php",
		method: "GET",
		data: {'parametros': '*'},
		dataType: 'JSON',
		success: function(data){
			
			if(data['resultado'] && data['dados'].length > 0)
			{
				console.log(data);
				$.each(data['dados'], function(chave, valor){
					let cardCategoria = $('#templateColecao').clone();
					cardCategoria.removeClass('none').removeAttr('id');
					cardCategoria.find('h2').html(valor['nome']);

					cardCategoria.find('a').click(function(){
						criaCookie("codCategoria", valor['cod_categoria']);
						window.location.replace("?pag=categoria");
					});

					$('.swiper-wrapper').append(cardCategoria);
				});

				iniciarSwiper();
			}
			
		}
	});
}

$(document).ready(function(){
	carregarColecoesPublicas();
});