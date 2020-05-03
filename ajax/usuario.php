<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/usuario.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadosCadastro = mapeaDadosRequest($_POST, array('nome', 'sobrenome', 'email', 'senha', 'deslogar'));
		$atualizaçãoImagem = isset($_FILES) ? $_FILES : null;

		if(count($atualizaçãoImagem))
		{
			$retorno = atualizaImagemUsuario($atualizaçãoImagem);
		}
		else if($dadosCadastro['deslogar'] == "true")
		{
			session_destroy();
			echo true;
			break;
		}
		else
		{
			$retorno = cadastrarUsuario($dadosCadastro);
		}

		echo json_encode($retorno);

		break;
	}

	case 'GET':
	{
		$dadosRequisicao = mapeaDadosRequest($_GET, array('parametros', 'nome', 'sobrenome', 'email', 'senha', 'codUsuario', 'imagemUsuario'));

		if($dadosRequisicao['senha'] != null)
		{
			$retorno = autenticaUsuario($dadosRequisicao);
		}
		else if(isset($dadosRequisicao['imagemUsuario']))
		{
			$retorno = retornaImagemUsuario();
		}
		else
		{
			if(!isset($dadosRequisicao['codUsuario']) && isset($_SESSION['user_info']))
			{
				$dadosRequisicao['codUsuario'] = $_SESSION['user_info']['cod_usuario'];
			}

			$retorno = buscaDadosUsuario($dadosRequisicao);
		}

		echo json_encode($retorno);

		break;
	}

	case 'PATCH':
	{
		parse_str(file_get_contents('php://input'), $_PATCH);

		$dadosRequisicao = mapeaDadosRequest($_PATCH, array('nome', 'sobrenome', 'email', 'displayName', 'descricao'));

		$retorno = atualizaDadosUsuario($dadosRequisicao);
		
		echo json_encode($retorno);

		break;
	}
}

