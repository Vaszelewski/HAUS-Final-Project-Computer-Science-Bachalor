<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/item.php');

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

		if(isset($dadosCadastro['atualizacaoImagem']))
		{
			$retorno = atualizarImagemItem($dadosCadastro);
		}
		else
		{
			$retorno = cadastrarItem($dadosCadastro);
		}

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{

		break;
	}

	case 'DELETE':
	{
		
		break;
	}
}

