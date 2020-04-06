<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/usuario.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('nome', 'sobrenome', 'email', 'senha'));

		$resultado = cadastrarUsuario($dadosCadastro);

		echo json_encode($resultado);
		break;
	}
}

