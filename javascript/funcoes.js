function exibeNotificacao(acao, msg){
	alertify.set('notifier','delay', 5);
	alertify.set('notifier', 'position', 'top-right');

	if(acao == "sucesso")
	{
		alertify.success("Sucesso! " + msg);
	}
	else if(acao == "alerta")
	{
		alertify.warning("Atenção! " + msg);
	}
	else
	{
		alertify.error("Erro! " + msg);
	}
}

function criaCookie(nome, valor, tempo) {
	let data = new Date(new Date().getTime() + tempo * 60 * 60 * 1000);
	document.cookie = nome + "=" + valor + ";expires=" + data.toUTCString();
}

function deletaCookie(nome){
	criaCookie(nome, "", -1);
}

function buscaCookie(nome){
	nome = nome + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let arrayCookie = decodedCookie.split(';');

	for(let i = 0; i <arrayCookie.length; i++){
		let cookiePartes = arrayCookie[i];

		while (cookiePartes.charAt(0) == ' '){
			cookiePartes = cookiePartes.substring(1);
		}

		if (cookiePartes.indexOf(nome) == 0){
			return cookiePartes.substring(nome.length, cookiePartes.length);
		}
	}
	return "";
}

function atualizaPreviewImagem(file, elementoPai){
	if(file)
	{
		if(file['type'].indexOf("image/") == 0)
		{
			let reader = new FileReader();

			reader.onload = function(){
				$(elementoPai + ' .imagemPreview > img').remove();

				var image = new Image();
				image.src = reader.result;
				$(elementoPai + ' .imagemPreview').append(image);
			}

			reader.readAsDataURL(file);
		}
		else
		{
			exibeNotificacao("alerta", 'O arquivo "' + file['name'] + '" não é uma imagem.');
		}
	}
}

function iniciarSwiper(){
	let parametros = {
		pagination: {
			el: '.swiper-pagination',
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		mousewheel: {
			invert: false,
			forceToAxis: true,
			releaseOnEdges: true,
		},
		freeMode: true,
		spaceBetween: 30,
		mousewheel: true,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
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
	}

	let swiper = new Swiper ('.swiper-container', parametros);

	return swiper;
}

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