function validacao(acao, msg){
    if(acao == "sucesso"){
        alertify.set('notifier','position', 'top-right');
        alertify.success(msg);
    }else{
        alertify.set('notifier','position', 'top-right');
        alertify.error(msg);
    }
}