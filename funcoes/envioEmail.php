<?php
	require_once('../mailer/PHPMailer.php');
	require_once('../mailer/SMTP.php');
	require_once('../mailer/Exception.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	function enviarEmaill($dadoEmail){
		$retorno = false;

		$sql = "
			SELECT
				COUNT(cod_usuario) AS total,display_name
			FROM
				usuario
			WHERE
				email LIKE '".bd_mysqli_real_escape_string($dadoEmail['email'])."'
		";
		$consulta = bd_consulta($sql);

		if($consulta[0]['total'] != 0){

			$formato_expiracao = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
			$data_expiracao = date("Y-m-d H:i:s",$formato_expiracao);
			$chave = md5($dadoEmail['email']);
			$addChave = substr(md5(uniqid(rand(),1)),3,10);
			$chave = $chave . $addChave;	
			$sqll = "
				INSERT INTO altera_senha(email,chave,data_expiracao)
				VALUES (
					'".bd_mysqli_real_escape_string($dadoEmail['email'])."',
					'".bd_mysqli_real_escape_string($chave)."',
					'".bd_mysqli_real_escape_string($data_expiracao)."'
				)";
			bd_insere($sqll);

			$mail = new PHPMailer();
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			//$mail->SMTPDebug = 1;
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "tls";
			$mail->Port = "587";
			$mail->Username = "noreplyhauscolecao@gmail.com";
			$mail->Password = "haus1006";
			$mail->Subject = "Recuperacao de Senha - HAUS";
			$mail->setFrom("noreply@haus.com");
			$mail->FromName = ("HAUS - Casa do Colecionador");
			$variavel = "
				<html>
					<body>
						<div style=\"text-align:center\">
							<div style=\"font-size: large;\">
								<img data-original-height=\"94\" data-original-width=\"400\" src=\"https://1.bp.blogspot.com/-oXzvmm9bbu8/XtW1j1snBKI/AAAAAAAAK7c/5aVuUOOSbb0ClDmm_kUhEKOwFI1UVv5CQCLcBGAsYHQ/s320/logo.png\"/>
								<h1>Olá ".$consulta[0]['display_name']." </h1>
								<p>Recebemos uma solicitação para redefinir sua senha do HAUS.</p>
								<p>Clique no botão para redefinição de senha a seguir:</p>
							</div>

							<div style=\"font-size: small;\">
								<a href=\"".$_SERVER['HTTP_ORIGIN']."/haus/index.php?pag=resetSenha&chave=".
								$chave."&email=".$dadoEmail['email']."&acao=reset\"><button 
								style=\"font-family: 'Josefin Sans', sans-serif; background: #20c997; padding: 15px; cursor: pointer; color: #fff; border: none; font-size: 15px;\">Alterar Senha</button></a>
							</div>
							<div style=\"font-size: large;\">
								<p>Caso o botão não funcione, copie e cole o endereço abaixo no seu navegador de Internet.</p>
								".$_SERVER['HTTP_ORIGIN']."/haus/index.php?pag=resetSenha&chave=".
								$chave."&email=".$dadoEmail['email']."&acao=reset
								<br>
								<p>Este link irá expirar após 24 horas por questões de segurança.</p><br>
								<br>
								<p>Não solicitou esta alteração?</p>
								<p>Se você não solicitou a alteração da senha, avise-nos (AQUI LINK SUPORTE).</p><br>
							</div>
							<br>
							<p>Essa mensagem foi enviada para ".$dadoEmail['email']." a seu pedido.</p>
							<p>HAUS</p>
						</div>
					</body> 
				</html>";
				$mail->MsgHTML($variavel);
				$mail->Body = $variavel;
			$mail->addAddress($dadoEmail['email']);
			$retorno = $mail->send();
			$mail->smtpClose();
		}else{
			$retorno = false;
		}
	return $retorno;
	}
?>