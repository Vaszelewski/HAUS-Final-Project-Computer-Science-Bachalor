<?php
/**
 * Acionar Suporte.
 * @param Array $dadosCadastro informacões do problema no site a ser reportado
 * @return Int retorna o id do reporte criado.
 */
function cadastrarSuporte($dadosSuporte){
	$retorno = array('resultado' => false, 'log' => "Usuário não autenticado.");

	if(isset($_SESSION['user_info'])){
		$sql = "
			INSERT INTO
				suporte(cod_opcao, cod_usuario, email, assunto, mensagem)
			VALUES (
				'".bd_mysqli_real_escape_string($dadosSuporte['codOpcao'])."',
				'".bd_mysqli_real_escape_string($_SESSION['user_info']['cod_usuario'])."',
				'".bd_mysqli_real_escape_string($dadosSuporte['email'])."',
				'".bd_mysqli_real_escape_string($dadosSuporte['assunto'])."',
				'".bd_mysqli_real_escape_string($dadosSuporte['mensagem'])."'
			)
		";

		$cadastraSuporte = bd_insere($sql);

		if(is_numeric($cadastraSuporte) && $cadastraSuporte != 0){
			$retorno['resultado'] = true;
			$retorno['log'] = "";
		}
	}

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