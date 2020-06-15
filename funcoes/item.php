<?php
/**
 * Responsável por realizar as buscas nos itens
 * @param Array $dadosBusca lista de parametros a ser buscada
 * @return Array retorna o resultado da pesquisa realizada.
 */
function buscarItem($dadosBusca){
	$retorno = array("resultado" => false, "mensagem" => "Categoria não encontrada.", "dados" => array());

	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosBusca['parametros'])."
		FROM
			item
		WHERE
			1
			[%1]
	";

	$codColecao = "";

	if(isset($dadosBusca['codColecao']))
	{
		$codColecao = "AND cod_colecao = ".bd_mysqli_real_escape_string($dadosBusca['codColecao']);
	}

	$sql = str_replace( 
		array('[%1]'),
		array($codColecao),
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
		$retorno['mensagem'] = "";
		$retorno['dados'] = $dadosBusca;
	}

	return $retorno;
}

/**
 * Responsável por realizar o cadastro de itens
 * @param Array $item contem os dado que serão cadastrados referente ao item.
 * @return Array 
 * 	resultado: informa se a insersão ocorreu com sucesso ou falha
 * 	log: em caso de erro informa o erro ocorrido.
 */
function cadastrarItem($item){
	$retorno = array("resultado" => false, "log" => "Falha no cadastro do item.");

	if(
		isset($item['titulo']) && isset($item['codColecao']) && isset($item['descricao']) &&
		!empty($item['imagemItem']) && ehDonoColecao($item['codColecao'])
	  )
	{
		$imagem = preparaDadosImagem($item['imagemItem']);

		$sql = "
			INSERT INTO
				item(titulo, descricao, cod_colecao, tipo_mime, imagem)
			VALUES (
				'".bd_mysqli_real_escape_string($item['titulo'])."',
				'".bd_mysqli_real_escape_string($item['descricao'])."',
				'".bd_mysqli_real_escape_string($item['codColecao'])."',
				'".bd_mysqli_real_escape_string($imagem['tipo_mime'])."',
				'".bd_mysqli_real_escape_string($imagem['conteudo'])."'
			)
		";

		if(bd_insere($sql) > 0)
		{
			$retorno['resultado'] = true;
			$retorno['log'] = "";
		}
	}
	
	return $retorno;
}

/**
 * Responsável por realizar a exclusão de um item
 * @param Int $codItem código do item que será excluido
 * @param Int $codColecao código da coleção para verificação de direito de exclusão
 * @return Boolean true em caso de sucesso na exclusão, se não false
 */
function excluiItem($codItem, $codColecao){
	$retorno = false;

	if(ehDonoColecao($codColecao))
	{
		$sql = "
			DELETE FROM
				item
			WHERE
				cod_item = ".bd_mysqli_real_escape_string($codItem)."
		";

		$retorno = bd_exclui($sql);
	}

	return $retorno;
}