<?php
require_once('bancoDeDados.php');

session_start();

/**
 * Realiza os requires dos scripts html para nevegação da aplicação
 * @param String $pagina pagina para onde deve ser realizado o require.
 * @return Void
 */
function paginacao($pagina){
	require_once('html/'.$pagina.'.html');
}

/**
 * Mapea os dados recebidos retornando apenas os dados solicitados que estão setados
 * @param Array $dados valores a serem setados
 * @param Array $chaves indices que devem ser retornados se existirem
 * @return Array retorna um array nomeado com os valores que foram encontrados nos dados.
 */
function mapeaDadosRequest($dados, $chaves){
	$retorno = array();

	foreach($chaves as $valor)
	{
		if(isset($dados[$valor]) && $dados[$valor] !== '')
		{
			$retorno[$valor] = is_array($dados[$valor]) ? $dados[$valor] : trim($dados[$valor]);
		}
		else
		{
			$retorno[$valor] = null;
		}
	}

	return $retorno;
}

/**
 * Função para criptografia da senha de usuário
 * @param String $senha senha a ser criptografada
 * @return Hash retorna senha criptografada.
 */
function encripta($senha){
	return password_hash($senha, PASSWORD_DEFAULT);
}

/**
 * Função para deletar requisição de reset de senha
 * @param String $dadoReset email da requisição a ser excluida
 */
function deletaRequisicao($dadoReset){
	$deletaEmail = "
			DELETE FROM altera_senha
			WHERE 
				email LIKE '".bd_mysqli_real_escape_string($dadoReset['email'])."'
	";		
	//ATUALIZAR COM A FUNÇÃO BD_EXCLUI() DEPOIS
	bd_atualiza($deletaEmail);
}

function buscaRequisicao($dadoReset){
	$sql = "
			SELECT
				data_expiracao, email
			FROM
				altera_senha
			WHERE
				chave LIKE '".bd_mysqli_real_escape_string($dadoReset['chave'])."'
			";
	$consulta = bd_consulta($sql);

	return $consulta;
}
