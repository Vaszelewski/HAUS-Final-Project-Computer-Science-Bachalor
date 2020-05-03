<?php
/**
 * Consulta a tabela de usuário.
 * @param Array $dadosBusca recebe os dados que devem ser buscados.
 * @return Array retorna os dados encontrados referente a pesquisa realizada, se nenhum dado for encontrado retorna um array vazio
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
			[%2]
	";

	$codUsuario = isset($dadosBusca['codUsuario']) ? "AND cod_usuario = ".bd_mysqli_real_escape_string($dadosBusca['codUsuario']) : "";
	$email = isset($dadosBusca['email']) ? "AND email LIKE '".bd_mysqli_real_escape_string($dadosBusca['email'])."'" : "";

	$sql = str_replace(
		array('[%1]', '[%2]'),
		array($codUsuario, $email),
		$sql
	);

	$retorno = bd_consulta($sql);

	return $retorno;
}

/**
 * Cadastrar um usuário.
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
 * Autenticar um usuário.
 * 	Esta função também é responsável por popular a sessão com as informações do usuário no caso das credenciais serem válidas.
 * @param Array $usuario credenciais para autenticação
 * @return Boolean informa true caso as credenciais sejam válidas, se não false.
 */
function autenticaUsuario($usuario){
	$retorno = false;

	$dadosBusca = array(
		"parametros" => 'cod_usuario, nome, sobrenome, display_name, email, senha',
		"email" => $usuario['email']
	);

	$dadosUsuario = buscaDadosUsuario($dadosBusca);

	if(!empty($dadosUsuario) && password_verify($usuario['senha'], $dadosUsuario[0]['senha']))
	{
		$_SESSION['user_info'] = $dadosUsuario[0];
		$_SESSION['user_info']['imagem'] = retornaImagemUsuario();

		$retorno = true;
	}

	return $retorno;
}

/**
 * Atualiza um usuário.
 * @param Array $novosDadosUsuario dados para a atualização
 * @return Boolean retorna verdadeiro caso a atualização tenha ocorrido com sucesso, senão falso.
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

	if(isset($novosDadosUsuario['nome']))
	{
		$sqlSet[] = "nome = '".bd_mysqli_real_escape_string($novosDadosUsuario['nome'])."'";
	}

	if(isset($novosDadosUsuario['sobrenome']))
	{
		$sqlSet[] = "sobrenome = '".bd_mysqli_real_escape_string($novosDadosUsuario['sobrenome'])."'";
	}

	if(isset($novosDadosUsuario['displayName']))
	{
		$sqlSet[] = "display_name = '".bd_mysqli_real_escape_string($novosDadosUsuario['displayName'])."'";
	}

	if(isset($novosDadosUsuario['descricao']))
	{
		$sqlSet[] = "descricao = '".bd_mysqli_real_escape_string($novosDadosUsuario['descricao'])."'";
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

	$retorno = is_numeric(bd_atualiza($sql));

	if($retorno)
	{
		$dadosBusca = array(
			"parametros" => 'cod_usuario, nome, display_name',
			"codUsuario" => $_SESSION['user_info']['cod_usuario']
		);

		$dadosAtualizados = buscaDadosUsuario($dadosBusca);

		$_SESSION['user_info']['nome'] = $dadosAtualizados[0]['nome'];
		$_SESSION['user_info']['display_name'] = $dadosAtualizados[0]['display_name'];
	}

	return $retorno;
}

/**
 * Retorna o base64 da imagem do usuário logado.
 * @return String base64 da imagem do usuário.
 */
function retornaImagemUsuario(){
	$retorno = "imagens/usuarioSemFoto.jpg";

	$sql = "
		SELECT
			tipo_mime, imagem
		FROM
			usuario
		WHERE
			cod_usuario = ".$_SESSION['user_info']['cod_usuario'];
	
	$buscaImagem = bd_consulta($sql);

	if(!empty($buscaImagem))
	{
		$retorno =  !empty($buscaImagem[0]['imagem']) 
					? "data:".$buscaImagem[0]['tipo_mime'].";base64,".base64_encode($buscaImagem[0]['imagem']).""
					: $retorno;
	}

	return $retorno;
}

/**
 * Atualiza a imagem do usuário.
 * @param Array $imagem informações da imagem
 * @return Int retorna 0 no caso de falha e 1 no caso de sucesso durante a atualização
 */
function atualizaImagemUsuario($imagem){
	$retorno = 0;

	if(
		!empty($imagem[0]['name']) && $imagem[0]['size'] > 0 &&
		$imagem[0]['size'] < 2000000 && is_numeric(strpos($imagem[0]['type'], 'image/'))
	  )
	{
		$conteudo = "";
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

		$retorno = bd_atualiza($sql);

		if($retorno)
		{
			$_SESSION['user_info']['imagem'] = retornaImagemUsuario();
		}
	}

	return $retorno;
}