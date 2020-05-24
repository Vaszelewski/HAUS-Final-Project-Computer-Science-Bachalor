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
			[%2]
	";

	$categoria = $buscaColecoesUsuario = "";

	if(isset($dadosBusca['codCategoria']))
	{
		$categoria = "AND cod_categoria = ".bd_mysqli_real_escape_string($dadosBusca['codCategoria']);
	}

	if(isset($dadosBusca['buscaColecoesUsuario']))
	{
		$buscaColecoesUsuario = "AND cod_colecao IN (
														SELECT
															cod_colecao
														FROM
															rel_usuarioxcolecao
														WHERE
															cod_usuario = ".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."
													)";
	}

	$sql = str_replace( 
		array('[%1]', '[%2]'),
		array($categoria, $buscaColecoesUsuario),
		$sql
	);

	$retorno = bd_consulta($sql);

	if(!empty($retorno) && isset($retorno[0]['imagem']))
	{
		foreach($retorno as $chave => $valor)
		{
			$retorno[$chave]['imagem'] =	!empty($valor['imagem']) 
											? "data:".$valor['tipo_mime'].";base64,".base64_encode($valor['imagem']).""
											: "";
			unset($retorno[$chave]['tipo_mime']);
		}
	}

	return $retorno;
}

/**
 * 
 */
function cadastrarColecao($colecao){
	$retorno = array("resultado" => false, "log" => "Falha no cadastro de coleção");

	$dadosCapa = preparaDadosImagem($colecao['capa']);

	$sql = "
		INSERT INTO
			colecao(nome, descricao, cod_categoria, tipo_mime, imagem)
		VALUES (
			'".bd_mysqli_real_escape_string($colecao['titulo'])."',
			'".bd_mysqli_real_escape_string($colecao['descricao'])."',
			'".bd_mysqli_real_escape_string($colecao['codCategoria'])."',
			'".bd_mysqli_real_escape_string($dadosCapa['tipo_mime'])."',
			'".bd_mysqli_real_escape_string($dadosCapa['conteudo'])."'
		)
	";

	$codColecao = bd_insere($sql);

	if($codColecao > 0)
	{
		$retorno = relacionaColecaoUsuario($codColecao);
	}

	return $retorno;
}

/**
 * 
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

/**
 * 
 */
function relacionaColecaoUsuario($codColecao){
	$retorno = array("resultado" => false, "log" => "Falha ao relacionar usuário a coleção, coleção não criada.");

	$sql = "
		INSERT INTO
			rel_usuarioxcolecao(cod_usuario, cod_colecao, dono)
		VALUES (
			'".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."',
			'".bd_mysqli_real_escape_string($codColecao)."',
			'".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."'
		)
	";

	$resultadoRelacionamento = bd_insere($sql, true);

	if($resultadoRelacionamento > 0)
	{
		$retorno['resultado'] = true;
		$retorno['log'] = "";
	}

	if(!$retorno['resultado'])
	{
		excluiColecao($codColecao);
	}

	return $retorno;
}

/**
 * 
 */
function excluiColecao($codColecao){
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