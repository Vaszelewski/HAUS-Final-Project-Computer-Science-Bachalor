<?php
function cadastrarUsuario($dados){
	$sql = "
		INSERT INTO
			usuario(nome, sobrenome, email, senha)
		VALUES (
			'".bd_mysqli_real_escape_string($dados['nome'])."',
			'".bd_mysqli_real_escape_string($dados['sobrenome'])."',
			'".bd_mysqli_real_escape_string($dados['email'])."',
			'".criptografaSenha($dados['senha'])."'
		)
	";

	if(validaUsuarioExistente($dados['email']))
	{
		return bd_insere($sql);
	}

	return 0;
}

function validaUsuarioExistente($email){
	$sql = "
		SELECT
			COUNT(id) AS total
		FROM
			usuario
		WHERE
			email LIKE '".bd_mysqli_real_escape_string($email)."'
	";

	$consulta = bd_consulta($sql);

	return !empty($consulta) && $consulta[0]['total'] == 0 ? true : false;
}