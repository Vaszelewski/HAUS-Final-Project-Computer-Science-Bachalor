<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/envioEmail.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadoEmail = mapeaDadosRequest($_POST, array('email'));
		$retorno = enviarEmaill($dadoEmail);
		echo json_encode($retorno);
		break;
	}
}