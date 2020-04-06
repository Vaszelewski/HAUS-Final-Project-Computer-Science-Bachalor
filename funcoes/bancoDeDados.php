<?php

function bd_conecta(){
	return mysqli_connect("localhost", "public_haus", 'Teste1234567890', "haus");
}

function bd_mysqli_real_escape_string($valor){
	$conexao = bd_conecta();
	return mysqli_real_escape_string($conexao, $valor);
}

function bd_insere($sql){
	$resultado = 0;
	$conexao = bd_conecta();

	if(!$conexao)
	{
		return $resultado;
	}

	if(mysqli_query($conexao, $sql))
	{
		$resultado = mysqli_insert_id($conexao);
	}
	else
	{
		$resultado = mysqli_error($conexao);
	}

	return $resultado;
}

function bd_atualiza(){

}

function bd_consulta($sql){
	$resultado = array();
	$conexao = bd_conecta();

	if(!$conexao)
	{
		return $resultado;
	}

	$executaQuery = mysqli_query($conexao, $sql);

	if($executaQuery)
	{
		while($linha = mysqli_fetch_assoc($executaQuery)){
			$resultado[] = $linha;
		}
	}
	else
	{
		$resultado = mysqli_error($conexao);
	}

	return $resultado;
}

function bd_exclui(){

}