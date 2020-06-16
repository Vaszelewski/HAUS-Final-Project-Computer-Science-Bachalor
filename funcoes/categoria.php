<?php
/**
 * Responsável por buscar dados de categorias.
 * @param Array $dadosBusca parametros para busca
 * @return Array dados da busca
 */
function buscaCategoria($parametros){
	$retorno = array("resultado" => false, "mensagem" => "Categoria não encontrada.", "dados" => array());

	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($parametros['parametros'])."
		FROM
			categoria
		WHERE
			1
			[%1]
			[%2]
			[%3]
	";

	$nome = isset($parametros['nome']) ? "AND nome LIKE '".bd_mysqli_real_escape_string($parametros['nome'])."'" : "";
	$categoriasUtilizadas = isset($parametros['categoriasUtilizadas']) ? "AND cod_categoria IN (SELECT cod_categoria FROM colecao)" : "";
	$orderBy = isset($parametros['ordenada']) ? "ORDER BY qtd_visualizacao DESC" : "";

	$sql = str_replace(
		array('[%1]', '[%2]', '[%3]'),
		array($nome, $categoriasUtilizadas, $orderBy),
		$sql
	);

	$dadosBusca = bd_consulta($sql);

	if(isset($parametros['categoriasUtilizadas']) && !empty($dadosBusca))
	{
		foreach ($dadosBusca as $chave => $valor)
		{
			$dadosColecao = buscarColecao(array("parametros" => 'tipo_mime, imagem', "codCategoria" => $valor['cod_categoria']));
			$dadosBusca[$chave]['imagem'] = $dadosColecao['dados'][0]['imagem'];
		}
	}

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
	$retorno = array('resultado' => false, 'log' => "Falha ao cadastrar categoria.");

	$sql = "
		INSERT INTO
			categoria(nome)
		VALUES (
			'".bd_mysqli_real_escape_string($categoria['nome'])."'
		)
	";

	if(verificaExistenciaCategoria($categoria['nome']))
	{
		if(bd_insere($sql) > 0){
			$retorno['resultado'] = true;
			$retorno['log'] = "";
		}
	}
	else
	{
		$retorno['log'] = "Categoria já existe.";
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

/**
 * 
 */
function atualizarCategoria($dadosAtualizacao){
	$retorno = array("resultado" => false, "log" => "Falha na atualização.");

	$sql = "
		UPDATE
			categoria
		SET
			qtd_visualizacao = qtd_visualizacao + 1
		WHERE
			cod_categoria = ".bd_mysqli_real_escape_string($dadosAtualizacao['codCategoria']);

	if(bd_atualiza($sql))
	{
		$retorno['resultado'] = true;
		$retorno['log'] = "";
	}

	return $retorno;
}