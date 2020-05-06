<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('nome', 'descricao', 'cod_categoria', 'privacidade'));

		$retorno = cadastrarColecao($dadosCadastro);

		echo json_encode($retorno);

		break;
	}

	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'codCategoria'));
		
		$retorno = buscarColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{
		parse_str(file_get_contents('php://input'), $_PATCH);

		$dadosRequisicao = mapeaDadosRequest($_PATCH, array('nome', 'descricao', 'cod_categoria', 'privacidade'));

		$retorno = atualizaColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}
}

