<?php

function cadastrarUsuario($dados){

	$senha = criptografaSenha($dados['senha']);

	$sql = "
		INSERT INTO
			usuario(nome, sobrenome, email, senha)
		VALUES (
			'".bd_mysqli_real_escape_string($dados['nome'])."',
			'".bd_mysqli_real_escape_string($dados['sobrenome'])."',
			'".bd_mysqli_real_escape_string($dados['email'])."',
			'".$senha."'
		)
	";

	return bd_insere($sql);

}