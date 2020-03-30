<?php
require_once('../funcoes/funcoes.php');
//require_once('../funcoes/cadastro.php');
syslog(LOG_ERR, var_export($_REQUEST ,true));
switch($_REQUEST){
	case 'POST':{
		$dadosCadastro = mapeaDadosRequest($_POST, array('nome', 'sobrenome', 'email', 'senha'));

		echo json_decode($dadosCadastro);
	}
}

REDWIBXX-GDBK9-F67RF-URU5E-E1ZFN