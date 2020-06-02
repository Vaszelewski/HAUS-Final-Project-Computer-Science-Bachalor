<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/resetSenha.php');
require_once('../funcoes/usuario.php');
require_once('../funcoes/bancoDeDados.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadoReset = mapeaDadosRequest($_POST, array('chave', 'email', 'acao', 'senha'));
		$retorno = resetSenha($dadoReset);
		echo json_encode($retorno);
		break;
	}
}

