<?php
	function resetSenha($dadoReset){
		$retorno = false;
		$atualData = date("Y-m-d H:i:s");

		$consulta = buscaRequisicao($dadoReset);

		if(!empty($consulta) && $dadoReset['email'] == $consulta[0]['email']){

			if($atualData <= $consulta[0]['data_expiracao']){
				
				$retorno = atualizaSenha($dadoReset);
				deletaRequisicao($dadoReset);

			}else{
				$retorno = false;
			}
		}else{
			$retorno = false;
		}
	return $retorno;
	}
?>