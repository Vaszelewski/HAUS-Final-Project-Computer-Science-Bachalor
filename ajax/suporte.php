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
		$dadosSuporte = mapeaDadosRequest($_POST, array('codOpcao','cod_usuario', 'email', 'assunto', 'mensagem'));
		if(!isset($dadosSuporte['codUsuario']) && isset($_SESSION['user_info'])){
			$dadosSuporte['codUsuario'] = $_SESSION['user_info']['cod_usuario'];
			$retorno = cadastrarSuporte($dadosSuporte);
		}
		echo json_encode($retorno);
		break;
	}
}