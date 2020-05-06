<?php
/**
 * 
 */
function buscaCategoria($dadosBusca){
	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosBusca['parametros'])."
		FROM
			categoria
		WHERE
			1
			[%1]
	";

	$nome = isset($dadosBusca['nome']) ? "AND nome LIKE '".bd_mysqli_real_escape_string($dadosBusca['nome'])."'" : "";

	$sql = str_replace( 
		array('[%1]'),
		array($nome),
		$sql
	);

	$retorno = bd_consulta($sql);

	return $retorno;
}

/**
 * 
 */
function cadastrarCategoria($categoria){
	$retorno = 0;

	$sql = "
		INSERT INTO
			categoria(nome)
		VALUES (
			'".bd_mysqli_real_escape_string($categoria['nome'])."'
		)
	";

	if(verificaExistenciaCategoria($categoria['nome']))
	{
		$retorno = bd_insere($sql);
	}

	return $retorno;
}

/**
 * 
 */
function verificaExistenciaCategoria($nome){
	$retorno = false;
	$categoria = buscaCategoria(array("parametros" => 'nome', "nome" => $nome));
	
	if(empty($categoria))
	{
		$retorno = true;
	}

	return $retorno;
}