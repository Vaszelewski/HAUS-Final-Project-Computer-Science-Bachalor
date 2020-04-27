<?php
/**
 * 
 */
function buscaDadosUsuario($dadosBusca){
	$sql = "
		SELECT
			".bd_mysqli_real_escape_string($dadosBusca['parametros'])."
		FROM
			usuario
		WHERE
			1
			[%1]
	";

	$codUsuario = isset($dadosBusca['codUsuario']) ? "AND cod_usuario = ".bd_mysqli_real_escape_string($dadosBusca['codUsuario']) : "";

	$sql = str_replace(
		array('[%1]'),
		array($codUsuario),
		$sql
	);

	$retorno = bd_consulta($sql);

	return $retorno;
}

/**
 * Reponsável por cadastrar um usuário.
 * @param Array $dadosCadastro informacões do usuário a ser criado
 * @return Int retorna 0 em caso de falha ao cadastrar ou o id do usuário criado.
 */
function cadastrarUsuario($dadosCadastro){
	$retorno = 0;
	
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
		$retorno = bd_insere($sql);
	}

	return $retorno;
}

/**
 * Válida se o email informado já esta cadastrao na base de dados.
 * @param String $email email a ser verificado
 * @return Boolean caso o usuário exista retorno true, se não false.
 */
function verificaUsuarioExistente($email){
	$sql = "
		SELECT
			COUNT(cod_usuario) AS total
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
	$retorno = false;

	$sql = "
		SELECT
			cod_usuario, nome, sobrenome, email, senha
		FROM
			usuario
		WHERE
			email LIKE '".bd_mysqli_real_escape_string($usuario['email'])."'
	";

	$dadosUsuario = bd_consulta($sql);

	if(!empty($dadosUsuario) && password_verify($usuario['senha'], $dadosUsuario[0]['senha']))
	{
		$_SESSION['user_info'] = $dadosUsuario[0];
		$_SESSION['user_info']['imagem'] = retornaImagemUsuario();

		$retorno = true;
	}

	return $retorno;
}

/**
 * 
 */
function atualizaDadosUsuario($novosDadosUsuario){
	$sql = "
		UPDATE
			usuario
		SET
			[%1]
		WHERE
			cod_usuario = ".$_SESSION['user_info']['cod_usuario'];

	$sqlSet = array();

	$dadosUsuario = buscaDadosUsuario(array("parametros" => 'email', "codUsuario" => $_SESSION['user_info']['cod_usuario']));

	if(isset($novosDadosUsuario['nome']))
	{
		$sqlSet[] = "nome = '".bd_mysqli_real_escape_string($novosDadosUsuario['nome'])."'";
	}

	if(isset($novosDadosUsuario['sobrenome']))
	{
		$sqlSet[] = "sobrenome = '".bd_mysqli_real_escape_string($novosDadosUsuario['sobrenome'])."'";
	}

	if(isset($novosDadosUsuario['email']))
	{
		if(!verificaUsuarioExistente($novosDadosUsuario['email']))
		{
			$sqlSet[] = "email = '".bd_mysqli_real_escape_string($novosDadosUsuario['email'])."'";
		}
	}

	$sql = str_replace(
		'[%1]',
		implode(', ', $sqlSet),
		$sql
	);

	$retorno = bd_atualiza($sql);

	if($retorno && $novosDadosUsuario['imagem'])
	{
		atualizaImagemUsuario($novosDadosUsuario['imagem']);
	}

	return $retorno;
}

/**
 * 
 */
function retornaImagemUsuario(){
	$retorno = "imagens/usuarioSemFoto.jpg";

	$sql = "
		SELECT
			imagem
		FROM
			usuario
		WHERE
			cod_usuario = ".$_SESSION['user_info']['cod_usuario'];
	
	$buscaImagem = bd_consulta($sql);

	if(!empty($buscaImagem))
	{
		$retorno = !empty($buscaImagem[0]['imagem']) ? "data:image/png;base64,".base64_encode($buscaImagem[0]['imagem'])."" : $retorno;
	}

	return $retorno;
}

/**
 * 
 */
function atualizaImagemUsuario($imagem){
	$retorno = array('atualizacao' => false);

	if(
		!empty($imagem[0]['name']) && $imagem[0]['size'] > 0 &&
		$imagem[0]['size'] < 2000000 && is_numeric(strpos($imagem[0]['type'], 'image/'))
	  )
	{
		$handle = fopen($imagem[0]['tmp_name'], "r");

		while(!feof($handle))
		{
			$conteudo .= fgets($handle);
		}

		fclose($handle);

		$sql = "
			UPDATE
				usuario
			SET
				imagem = '".bd_mysqli_real_escape_string($conteudo)."',
				tipo_mime = '".bd_mysqli_real_escape_string($imagem[0]['type'])."'
			WHERE
				cod_usuario = ".$_SESSION['user_info']['cod_usuario'];

		$retorno['atualizacao'] = bd_atualiza($sql);
	}

	return $retorno;
}