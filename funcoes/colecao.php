<?php
/**
 * 
 */
function buscarColecao($dadosBusca){
	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosBusca['parametros'])."
		FROM
			colecao
		WHERE
			1
			[%1]
	";

	$categoria = isset($dadosBusca['codCategoria']) ? "AND cod_categoria = ".bd_mysqli_real_escape_string($dadosBusca['codCategoria']) : "";

	$sql = str_replace( 
		array('[%1]'),
		array($categoria),
		$sql
	);

	$retorno = bd_consulta($sql);

	return $retorno;
}

/**
 * 
 */
function cadastrarColecao($colecao){
	$retorno = array("resultado" => false, "log" => "Falha no cadastro de coleção");

	$sql = "
		INSERT INTO
			colecao(nome, descricao, cod_categoria, privacidade)
		VALUES (
			'".bd_mysqli_real_escape_string($colecao['nome'])."',
			'".bd_mysqli_real_escape_string($colecao['descricao'])."',
			'".bd_mysqli_real_escape_string($colecao['codCategoria'])."',
			'".bd_mysqli_real_escape_string($colecao['privacidade'])."'
		)
	";

	$retorno['resultado'] = bd_insere($sql);

	if($retorno['resultado'])
	{
		$retorno = relacionaColecaoUsuario($retorno);
	}

	return $retorno;
}

/**
 * 
 */
function relacionaColecaoUsuario($codColecao){
	$retorno = array("resultado" => false, "log" => "Falha ao relacionar usuário a coleção");

	$sql = "
		INSERT INTO
			rel_usuarioxcolecao(cod_usuario, cod_colecao, dono)
		VALUES (
			'".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."',
			'".bd_mysqli_real_escape_string($codColecao)."',
			'".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."'
		)
	";

	$retorno['resultado'] = bd_insere($sql);

	if(!$retorno['resultado'])
	{
		desfazCriacaoColecao($codColecao);
	}

	return $retorno;
}

/**
 * 
 */
function desfazCriacaoColecao($codColecao){
	$sql = "
		DELETE FROM
			colecao
		WHERE
			cod_colecao = ".bd_mysqli_real_escape_string($codColecao)."
	";

	bd_exclui($sql);
}

/**
 * 
 */
function atualizaColecao($novosDadosColecao){
	$sql = "
		UPDATE
			colecao
		SET
			[%1]
		WHERE
			cod_colecao = ".bd_mysqli_real_escape_string($novosDadosColecao['codColecao']);

	$sqlSet = array();

	if(isset($novosDadosColecao['nome']))
	{
		$sqlSet[] = "nome = '".bd_mysqli_real_escape_string($novosDadosColecao['nome'])."'";
	}

	if(isset($novosDadosColecao['descricao']))
	{
		$sqlSet[] = "descricao = '".bd_mysqli_real_escape_string($novosDadosColecao['descricao'])."'";
	}

	if(isset($novosDadosColecao['codCategoria']))
	{
		$sqlSet[] = "cod_categoria = '".bd_mysqli_real_escape_string($novosDadosColecao['codCategoria'])."'";
	}

	if(isset($novosDadosColecao['privacidade']))
	{
		$sqlSet[] = "privacidade = '".bd_mysqli_real_escape_string($novosDadosColecao['privacidade'])."'";
	}


	$sql = str_replace(
		'[%1]',
		implode(', ', $sqlSet),
		$sql
	);

	return is_numeric(bd_atualiza($sql));
}