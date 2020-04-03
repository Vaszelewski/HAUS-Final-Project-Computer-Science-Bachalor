<?php
require_once('bancoDeDados.php');

/**
 * Mapea a os dados recebidos retornando um array nomeado.
 * @param array dados valores recebidos
 * @param array chaves indices a serem buscados no array de dados
 * @return array retorna um array com os valores que foram encontrados nos dados.
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
 * Retorna senha criptografada.
 * @return hash da senha informada.
 */
function criptografaSenha($senha){
	return password_hash($senha, PASSWORD_DEFAULT);
}