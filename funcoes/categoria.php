<?php
/**
 * 
 */
function buscaCategoria($dadosBusca){
	$retorno = array("resultado" => false, "mensagem" => "Categoria não encontrada.", "dados" => array());
	
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

	$dadosBusca = bd_consulta($sql);

	if(is_array($dadosBusca))
	{
		$retorno['resultado'] = true;
		$retorno['mensagem'] = "";
		$retorno['dados'] = $dadosBusca;
	}

	return $retorno;
}

/**
 * 
 */
function cadastrarCategoria($categoria){
	$retorno = array(
		'resultado' => false,
		'mensagem' => ""
	);

	$sql = "
		INSERT INTO
			categoria(nome)
		VALUES (
			'".bd_mysqli_real_escape_string($categoria['nome'])."'
		)
	";

	if(verificaExistenciaCategoria($categoria['nome']))
	{
		$retorno['resultado'] = bd_insere($sql);
	}
	else
	{
		$retorno['mensagem'] = "Categoria já existe.";
	}

	return $retorno;
}

/**
 * 
 */
function verificaExistenciaCategoria($nome){
	$retorno = false;
	$categoria = buscaCategoria(array("parametros" => 'nome', "nome" => $nome));

	if(empty($categoria['dados']))
	{
		$retorno = true;
	}

	return $retorno;
}