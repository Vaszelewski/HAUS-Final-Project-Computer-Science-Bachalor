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
 * Prepara os dados da imagem recebida para utilizar em query sql.
 * @param Array $imagem dados da imagem recebida
 * @return Array
 * 	conteudo: dados imagem para query
 * 	tipo_mime: extensão da imagem
 */
function preparaDadosImagem($imagem){
	$retorno = array('conteudo' => "", 'tipo_mime' => "");

	if(!empty($imagem[0]['name']) && $imagem[0]['size'] > 0 && is_numeric(strpos($imagem[0]['type'], 'image/')))
	{
		$conteudo = "";
		$handle = fopen($imagem[0]['tmp_name'], "r");

		while(!feof($handle))
		{
			$conteudo .= fgets($handle);
		}

		fclose($handle);

		$retorno['conteudo'] = $conteudo;
		$retorno['tipo_mime'] = $imagem[0]['type'];
	}

	return $retorno;
}
