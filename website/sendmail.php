<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
?>
<?php
function send_email($email, $html, $subject)
{
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->SMTPSecure = "tls";
	$mail->SMTPAuth = true;
	$mail->Username = "dbqmart@gmail.com";
	$mail->Password = "DBQ@12345";
	$mail->SetFrom("dbqmart@gmail.com");
	$mail->addAddress($email);
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->AddEmbeddedImage(dirname(__FILE__) . '/checked.png', 'logo');	
	$mail->Body = $html;
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	if ($mail->send()) {
		//echo "done";
	} else {
		//echo "Error occur";
	}
}
