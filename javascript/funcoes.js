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