<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/recuperacaoDeSenha.php');
require_once('../funcoes/usuario.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadoEmail = mapeaDadosRequest($_POST, array('email','acao'));

		if($dadoEmail['acao'] == "enviarEmail"){
			$retorno = enviarEmail($dadoEmail);
			echo json_encode($retorno);
			break;

		}else if ($dadoEmail['acao'] == "alterarSenha"){
			$dadoReset = mapeaDadosRequest($_POST, array('chave', 'email', 'acao', 'senha'));
			$retorno = alteraSenha($dadoReset);
			echo json_encode($retorno);
			break;
		}
	}

}