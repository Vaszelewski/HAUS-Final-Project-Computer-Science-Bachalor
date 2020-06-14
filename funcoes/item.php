<?php
/**
 * 
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
 * 
 */
function cadastrarItem($item){
	$retorno = array("resultado" => false, "log" => "Falha no cadastro do item.");

	if(isset($item['titulo']) && isset($item['codColecao']) && isset($item['descricao']) && !empty($item['imagemItem']))
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