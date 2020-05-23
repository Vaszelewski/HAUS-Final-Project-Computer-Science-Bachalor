<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/categoria.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'nome'));
	
		$retorno = buscaCategoria($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosRecebidos = preparaDadoRecebidos();
		$dadosCadastro = mapeaDadosRequest($dadosRecebidos, array('nome'));

		$retorno = cadastrarCategoria($dadosCadastro);

		echo json_encode($retorno);

		break;
	}
}

