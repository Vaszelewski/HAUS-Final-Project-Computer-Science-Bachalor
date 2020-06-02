<?php
/**
 * Responsável por buscar dados de categorias.
 * @param Array $dadosBusca parametros para busca
 * @return Array dados da busca
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
 * Responsável por cadastrar categoria
 * @param Array $categoria dados categoria
 * @return Array 
 * 	resultado: true em caso de sucesso ao cadastrar a categoria, se não false
 * 	log: em caso e falha retorna a mensagem de erro
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
 * Verifica se uma categoria já existe no sistema
 * @param String $nome informa o nome a categoria a ser verificada
 * @return Boolean true em caso da categoria não existir, se não falses
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