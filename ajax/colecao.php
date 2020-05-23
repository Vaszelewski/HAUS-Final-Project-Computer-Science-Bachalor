<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'codCategoria'));
		
		$retorno = buscarColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosRecebidos = preparaDadoRecebidos();
		$dadosCadastro = mapeaDadosRequest($dadosRecebidos, array('nome', 'descricao', 'codCategoria', 'privacidade'));
		echo json_encode($dadosCadastro);
		if(isset($dadosCadastro['nome']) && isset($dadosCadastro['descricao']) && isset($dadosCadastro['codCategoria']))
		{
			$retorno = cadastrarColecao($dadosCadastro);
		}

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{
		$dadosRecebidos = preparaDadoRecebidos();

		$dadosRequisicao = mapeaDadosRequest($dadosRecebidos, array('nome', 'descricao', 'cod_categoria', 'privacidade'));

		$retorno = atualizaColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}
}

