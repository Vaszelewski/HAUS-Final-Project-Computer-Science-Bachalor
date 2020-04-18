<?php
/**
 * Reponsável por cadastrar um usuário.
 * @param Array $dadosCadastro informacões do usuário a ser criado
 * @return Int retorna 0 em caso de falha ao cadastrar ou o id do usuário criado.
 */
function cadastrarUsuario($dadosCadastro){
	$resultado = 0;
	
	$sql = "
		INSERT INTO
			usuario(nome, sobrenome, email, senha)
		VALUES (
			'".bd_mysqli_real_escape_string($dadosCadastro['nome'])."',
			'".bd_mysqli_real_escape_string($dadosCadastro['sobrenome'])."',
			'".bd_mysqli_real_escape_string($dadosCadastro['email'])."',
			'".encripta($dadosCadastro['senha'])."'
		)
	";

	if(!verificaUsuarioExistente($dadosCadastro['email']))
	{
		$resultado = bd_insere($sql);
	}

	return $resultado;
}

/**
 * Válida se o email informado já esta cadastrao na base de dados.
 * @param String $email email a ser verificado
 * @return Boolean caso o usuário exista retorno true, se não false.
 */
function verificaUsuarioExistente($email){
	$sql = "
		SELECT
			COUNT(id) AS total
		FROM
			usuario
		WHERE
			email LIKE '".bd_mysqli_real_escape_string($email)."'
	";

	$consulta = bd_consulta($sql);

	return !empty($consulta) && $consulta[0]['total'] == 0 ? false : true;
}

/**
 * Responsável por autenticar um usuário.
 * 	Esta função também é responsável por popular a sessão com as informações do usuário no caso das credenciais serem válidas.
 * @param Array $usuario credenciais para autenticação
 * @return Boolean informa true caso as credenciais sejam válidas, se não false.
 */
function autenticaUsuario($usuario){
	$resultado = false;

	$sql = "
		SELECT
			nome, sobrenome, email, senha
		FROM
			usuario
		WHERE
			email LIKE '".bd_mysqli_real_escape_string($usuario['email'])."'
	";

	$dadosUsuario = bd_consulta($sql);

	if(!empty($dadosUsuario) && password_verify($usuario['senha'], $dadosUsuario[0]['senha']))
	{
		$_SESSION['user_info'] = $dadosUsuario[0];

		$resultado = true;
	}

	return $resultado;
}