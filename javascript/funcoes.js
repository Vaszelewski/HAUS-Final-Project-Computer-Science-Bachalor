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