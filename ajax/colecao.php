<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'buscaColecoesUsuario', 'codCategoria'));
		
		$retorno = buscarColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('titulo', 'codCategoria', 'descricao', 'atualizacaoCapa', 'codColecao'));
		$dadosCadastro['capa'] = isset($_FILES) ? $_FILES : null;

		if(isset($dadosCadastro['atualizacaoCapa']))
		{
			$retorno = atualizaCapaColecao($dadosCadastro);
		}
		else
		{
			$retorno = cadastrarColecao($dadosCadastro);
		}

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{
		parse_str(file_get_contents('php://input'), $dadosRecebidos);
		$dadosRequisicao = mapeaDadosRequest($dadosRecebidos, array('titulo', 'descricao', 'codCategoria', 'codColecao', 'registrarVisualizacao'));

		$retorno = atualizaColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'DELETE':
	{
		parse_str(file_get_contents('php://input'), $dadosRecebidos);

		$dadosRequisicao = mapeaDadosRequest($dadosRecebidos, array('codColecao'));
		$retorno = excluiColecao($dadosRequisicao['codColecao']);

		echo json_encode($retorno);

		break;
	}
}

