<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/item.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'codColecao'));
		
		$retorno = buscarItem($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('titulo', 'codColecao', 'descricao', 'atualizacaoImagem'));
		$dadosCadastro['imagemItem'] = isset($_FILES) ? $_FILES : null;

		$retorno = cadastrarItem($dadosCadastro);

		echo json_encode($retorno);

		break;
	}

	case 'DELETE':
	{
		parse_str(file_get_contents('php://input'), $dadosRecebidos);

		$dadosRequisicao = mapeaDadosRequest($dadosRecebidos, array('codColecao', 'codItem'));
		$retorno = excluiItem($dadosRequisicao['codItem'], $dadosRequisicao['codColecao']);

		echo json_encode($retorno);

		break;
	}
}

