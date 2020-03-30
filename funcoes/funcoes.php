<?php

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
			$retorno[$valor] = trim($dados[$valor]);
		}
		else
		{
			$retorno[$valor] = null;
		}
	}

	return $retorno;
}