<?php
require_once('bancoDeDados.php');

/**
 * Realiza os requires dos scripts html para nevegação da aplicação
 * @param string $pagina pagina para onde deve ser realizado o require.
 * @return void
 */
function paginacao($pagina){
	require_once('html/'.$pagina.'.html');
}

/**
 * Mapea os dados recebidos retornando apenas os dados solicitados que estão setados
 * @param array $dados valores a serem verificados
 * @param array $chaves indices que devem ser retornados se existirem
 * @return array retorna um array nomeado com os valores que foram encontrados nos dados.
 */
function mapeaDadosRequest($dados, $chaves){
	$retorno = array();

	foreach($chaves as $valor)
	{
		if(isset($dados[$valor]) && $dados[$valor] !== '')
		{
			$retorno[$valor] = is_array($dados[$valor]) ? $dados[$valor] : trim($dados[$valor]);
		}
		else
		{
			$retorno[$valor] = null;
		}
	}

	return $retorno;
}

/**
 * Função para criptografia da senha de usuário
 * @param string $senha senha a ser criptografada
 * @return hash retorna senha criptografada.
 */
function criptografaSenha($senha){
	return password_hash($senha, PASSWORD_DEFAULT);
}