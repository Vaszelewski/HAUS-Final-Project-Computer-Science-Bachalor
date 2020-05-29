<?php
require_once('../funcoes/funcoes.php');
require_once('../funcoes/usuario.php');

switch($_SERVER['REQUEST_METHOD'])
{
	case 'POST':
	{
		$dadoEmail = mapeaDadosRequest($_POST, array('email'));
		if(isset($_SESSION['user_info'])){
			enviarEmaill($dadoEmail);
		}
		break;
	}

}