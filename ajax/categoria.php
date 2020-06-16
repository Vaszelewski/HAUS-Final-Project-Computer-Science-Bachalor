<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/categoria.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'nome', 'categoriasUtilizadas', 'ordenada'));
	
		$retorno = buscaCategoria($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('nome'));

		$retorno = cadastrarCategoria($dadosCadastro);

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{
		parse_str(file_get_contents('php://input'), $dadosRecebidos);
		$dadosAtualizacao = mapeaDadosRequest($dadosRecebidos, array('codCategoria'));
		
		$retorno = atualizarCategoria($dadosAtualizacao);

		echo json_encode($retorno);

		break;
	}
}

