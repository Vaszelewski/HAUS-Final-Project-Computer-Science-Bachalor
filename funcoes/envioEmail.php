<?php
	function enviarEmaill($dadoEmail){
		$sql = "
				SELECT
					COUNT(cod_usuario) AS total
				FROM
					usuario
				WHERE
					email LIKE '".bd_mysqli_real_escape_string($email)."'
			";

		$consulta = bd_consulta($sql);
		if ($consulta==""){
			$error .= "<p>Nenhum usuario registrado com este email</p>";
		} else{
			$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
			$expDate = date("Y-m-d H:i:s",$expFormat);
			$key = md5(2418*2+$email);
			$addKey = substr(md5(uniqid(rand(),1)),3,10);
			$key = $key . $addKey;
			// Insert Temp Table
			$sqll = "INSERT INTO password_reset_temp(email, keyy, expDate)
					VALUES (
						'".bd_mysqli_real_escape_string($dadoEmail['email'])."',
						'".bd_mysqli_real_escape_string('keyy')."',
						'".bd_mysqli_real_escape_string('expDate')."',
					)";

			$output='<p>Caro Usuário,</p>';
			$output.='<p>Por favor clique no link abaixo para resetar sua senha.</p>';
			$output.='<p>-------------------------------------------------------------</p>';
			$output.='<p><a href="https://www.allphptricks.com/forgot-password/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">https://www.allphptricks.com/forgot-password/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>';		
			$output.='<p>-------------------------------------------------------------</p>';
			$output.='<p>Este link irá expirar em um 1 dia por questões de segurança.</p>';
			$output.='<p>Thanks,</p>';
			$body = $output; 
			$subject = "Password Recovery - HAUS - Casa do Colecionador"
			$email_to = $email;
			$fromserver = "noreply@hauscolecao.com"; 
			require("PHPMailer/PHPMailerAutoload.php");

			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = "mail.hauscolecao.com"; // Enter your host here
			$mail->SMTPAuth = true;
			$mail->Username = "noreply@hauscolecao.com"; // Enter your email here
			$mail->Password = "everton"; //Enter your passwrod here
			$mail->Port = 25;
			$mail->IsHTML(true);
			$mail->From = "noreply@hauscolecao.com";
			$mail->FromName = "HAUS - Casa do Colecionaodr";
			$mail->Sender = $fromserver; // indicates ReturnPath header
			$mail->Subject = $subject;
			$mail->Body = $body;
			$mail->AddAddress($email_to);
			if(!$mail->Send()){
				echo "Mailer Error: " . $mail->ErrorInfo;
			}else{
				echo "<div class='error'>
				<p>An email has been sent to you with instructions on how to reset your password.</p>
				</div><br /><br /><br />";
			}
		}
	}
?>