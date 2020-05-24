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
 * 
 */
function preparaDadoRecebidos(){
	$retorno = array();
	$headers = apache_request_headers();
	$contentType = explode(';', $headers['Content-Type']);

	switch($contentType[0])
	{
		case 'application/json':
		{
			$dados = file_get_contents('php://input');

			if(!empty($dados))
			{
				$retorno = json_decode($dados, true);
			}
			
			break;
		}
		case 'multipart/form-data':
		case 'application/x-www-form-urlencoded':
		{
			$retorno = is_array($_POST) ? $_POST : array();
			break;
		}
	}

	return $retorno;
}