<?php
/**
 * Responsável por realizar as buscas nas coleções
 * @param Array $dadosBusca lista de parametros a ser buscada
 * @return Array retorna o resultado da pesquisa realizada.
 */
function buscarColecao($dadosBusca){
	$retorno = array("resultado" => false, "log" => "Categoria não encontrada.", "dados" => array());

	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosBusca['parametros'])."
		FROM
			colecao
		WHERE
			1
			[%1]
			[%2]
			[%3]
	";

	$categoria = $buscaColecoesUsuario = $orderBy = "";

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

	if(!isset($dadosBusca['buscaColecoesUsuario']))
	{
		$orderBy = "ORDER BY qtd_visualizacao DESC";
	}

	$sql = str_replace( 
		array('[%1]', '[%2]', '[%3]'),
		array($categoria, $buscaColecoesUsuario, $orderBy),
		$sql
	);

	$dadosBusca = bd_consulta($sql);

	if(!empty($dadosBusca) && isset($dadosBusca[0]['imagem']))
	{
		foreach($dadosBusca as $chave => $valor)
		{
			$dadosBusca[$chave]['imagem'] =	!empty($valor['imagem']) 
											? "data:".$valor['tipo_mime'].";base64,".base64_encode($valor['imagem']).""
											: "";
			unset($dadosBusca[$chave]['tipo_mime']);
		}
	}

	if(is_array($dadosBusca))
	{
		$retorno['resultado'] = true;
		$retorno['log'] = "";
		$retorno['dados'] = $dadosBusca;
	}

	return $retorno;
}

/**
 * Responsável por realizar o cadastro de novas coleções
 * @param Array $colecao contem os dado que serão cadastrados referente a nova coleção.
 * @return Array 
 * 	resultado: informa se a insersão ocorreu com sucesso ou falha
 * 	log: em caso de erro informa o erro ocorrido.
 */
function cadastrarColecao($colecao){
	$retorno = array("resultado" => false, "log" => "Dados obrigátorios não enviados", "cod_colecao" => 0);

	if(isset($colecao['titulo']) && isset($colecao['codCategoria']) && isset($colecao['descricao']))
	{
		$dadosCapa = preparaDadosImagem($colecao['capa']);

		$sql = "
			INSERT INTO
				colecao(titulo, descricao, cod_categoria, tipo_mime, imagem)
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
			$retorno['cod_colecao'] = $codColecao;
		}
	}

	return $retorno;
}

/**
 * Responsável por vincular o usuário a coleção no ato da criação da coleção.
 * @param Int $codColecao código a coleção recem criada
 * @return Array
 * 	resultado: informa se a associação foi feita com sucesso
 * 	log: em caso de erro informa o erro ocorrido
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
 * Responsável por realizar a exclusão de uma coleção
 * @param Int $codColecao código da coleção que será excluida
 * @return Boolean true em caso de sucesso na exclusão, se não false
 */
function excluiColecao($codColecao){
	$retorno = false;

	if(ehDonoColecao($codColecao))
	{
		$sql = "
			DELETE FROM
				colecao
			WHERE
				cod_colecao = ".bd_mysqli_real_escape_string($codColecao)."
		";

		$retorno = bd_exclui($sql);
	}

	return $retorno;
}

/**
 * Verifica se o usuário logao no sistema é o dono da coleção.
 * @param Int $codColecao código da coleção que será verificada
 * @return Boolean retorna true caso o usuário seja o dono da coleção, se não false.
 */
function ehDonoColecao($codColecao){
	$sql = "
		SELECT
			COUNT(*) as total
		FROM
			colecao
			INNER JOIN
				rel_usuarioxcolecao ON
				colecao.cod_colecao = rel_usuarioxcolecao.cod_colecao
		WHERE
			colecao.cod_colecao = '".bd_mysqli_real_escape_string($codColecao)."' AND
			rel_usuarioxcolecao.dono = '".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."'
	";

	return bd_consulta($sql)[0]['total'] == "1" ? true : false;
}

/**
 * Responsável por efetuar atualização dos dados e uma coleção.
 * @param Array $novosDadosColecao dados recebidos para atualização.
 * @return Array
 * 	resultado: informa true caso a atualização tenha sucesso, se não false
 * 	log: se ocorrer falha retorna a mensagem de erro
 */
function atualizaColecao($novosDadosColecao){
	$retorno = array("resultado" => false, "log" => "Falha na atualização.");

	$sql = "
		UPDATE
			colecao
		SET
			[%1]
		WHERE
			cod_colecao = ".bd_mysqli_real_escape_string($novosDadosColecao['codColecao']);

	$sqlSet = array();

	if(isset($novosDadosColecao['titulo']))
	{
		$sqlSet[] = "titulo = '".bd_mysqli_real_escape_string($novosDadosColecao['titulo'])."'";
	}

	if(isset($novosDadosColecao['descricao']))
	{
		$sqlSet[] = "descricao = '".bd_mysqli_real_escape_string($novosDadosColecao['descricao'])."'";
	}

	if(isset($novosDadosColecao['codCategoria']))
	{
		$sqlSet[] = "cod_categoria = '".bd_mysqli_real_escape_string($novosDadosColecao['codCategoria'])."'";
	}

	if(isset($novosDadosColecao['registrarVisualizacao']))
	{
		$sqlSet = array();
		$sqlSet[] = "qtd_visualizacao = qtd_visualizacao + 1";
	}


	$sql = str_replace(
		'[%1]',
		implode(', ', $sqlSet),
		$sql
	);

	if(ehDonoColecao($novosDadosColecao['codColecao']) || $novosDadosColecao['registrarVisualizacao'])
	{
		if(bd_atualiza($sql))
		{
			$retorno['resultado'] = true;
			$retorno['log'] = "";
		}
	}
	else
	{
		$retorno['log'] = "Usuário não é dono da coleção";
	}

	return $retorno;
}

/**
 * Responsável por atulizar a imagem de capa da coleção.
 * @param Array $colecao dados da coleção
 * @return Array
 * 	resultado: informa true em caso de sucesso, se não false
 * 	log: em caso e falha retorna uma mensagem de erro.
 */
function atualizaCapaColecao($colecao){
	$retorno = array("resultado" => false, "log" => "Falha na alteração da capa");

	if(isset($colecao['codColecao']) && isset($colecao['capa']))
	{
		$dadosCapa = preparaDadosImagem($colecao['capa']);

		$sql = "
			UPDATE
				colecao
			SET
				imagem = '".bd_mysqli_real_escape_string($dadosCapa['conteudo'])."',
				tipo_mime = '".bd_mysqli_real_escape_string($dadosCapa['tipo_mime'])."'
			WHERE
				cod_colecao = '".bd_mysqli_real_escape_string($colecao['codColecao'])."'
		";

		if(bd_atualiza($sql))
		{
			$retorno['resultado'] = true;
			$retorno['log'] = "";
		}
	}

	return $retorno;
}