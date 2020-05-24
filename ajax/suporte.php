<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/suporte.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosOpcoesSuporte = mapeaDadosRequest($_GET, array('parametros'));
		$retorno = buscaOpcoesSuporte($dadosOpcoesSuporte);
		echo json_encode($retorno);
		break;
	}

	case 'POST':
	{
		$dadosSuporte = mapeaDadosRequest($_POST, array('codOpcao', 'email', 'assunto', 'mensagem'));

		if(isset($_SESSION['user_info'])){
			$retorno = cadastrarSuporte($dadosSuporte);
		}
		
		echo json_encode($retorno);
		break;
	}
}