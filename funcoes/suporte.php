<?php
/**
 * Acionar Suporte.
 * @param Array $dadosCadastro informacões do problema no site a ser reportado
 * @return Int retorna o id do reporte criado.
 */
function cadastrarSuporte($dadosSuporte){
	$retorno = 0;
	$sql = "
		INSERT INTO
			suporte(cod_opcao, cod_usuario, email, assunto, mensagem)
		VALUES (
			'".bd_mysqli_real_escape_string($dadosSuporte['codOpcao'])."',
			'".bd_mysqli_real_escape_string($dadosSuporte['codUsuario'])."',
			'".bd_mysqli_real_escape_string($dadosSuporte['email'])."',
			'".bd_mysqli_real_escape_string($dadosSuporte['assunto'])."',
			'".bd_mysqli_real_escape_string($dadosSuporte['mensagem'])."'
		)
	";

	$retorno = bd_insere($sql);
	return $retorno;
}

/**
 * Buscar Opções Suporte.
 * @param Array $dadosOpcoesSuporte opções de suporte que o problema é referenciado
 * @return String retorna uma lista de opçoes de suporte
 */
function buscaOpcoesSuporte($dadosOpcoesSuporte){
	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosOpcoesSuporte['parametros'])."
		FROM
			opcoes_suporte
	";

	$retorno = bd_consulta($sql);

	return $retorno;
}