<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/colecao.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'buscaColecoesUsuario'));
		
		$retorno = buscarColecao($dadosRequisicao);

		echo json_encode($retorno);

		break;
	}

	case 'POST':
	{
		$dadosRecebidos = preparaDadoRecebidos();
		$dadosCadastro = mapeaDadosRequest($dadosRecebidos, array('titulo', 'codCategoria', 'descricao'));
		$dadosCadastro['capa'] = isset($_FILES) ? $_FILES : null;
		
		if(isset($dadosCadastro['titulo']) && isset($dadosCadastro['codCategoria']) && isset($dadosCadastro['descricao']))
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

