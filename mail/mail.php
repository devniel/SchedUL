<?php
ini_set ('error_reporting', E_ALL);
session_start();

$_SESSION["confirmated"] = false;

function getUniqueCode($length = "")
{	
	$n = uniqid(rand(),true);
	$code = md5($n);
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}

$code = null;

// SEND EMAIL

if (isset($_POST['email'])) {
	
	$email = strtolower($_POST['email']);
	
	// REGEX //
	
	$correct = false;
	
	if(ereg("^([0-9]{8})@aloe.ulima.edu.pe" ,$email,$param)){
		echo "DIRECCION CORRECTA : $param[1]";
		$correct = true;
	}else{
		print "error";
	}
	
	if($correct){
			
		$code = getUniqueCode(32);
		$_SESSION["code"] = $code;
		
		/*$client = new SoapClient('https://api.jangomail.com/api.asmx?WSDL');
		$parameters = array
		(
			'Username' => (string) 'dnielF',
			'Password' => (string) '729280MN',
			'FromEmail' => (string) 'dnielfs@gmail.com',
			'FromName' => (string) 'SchedUL',
			'ToEmailAddress' => (string) $email,
			'Subject' => (string) 'Código de confirmación',
			'MessagePlain' => (string) 'Transactional Plain (plain text)',
			'MessageHTML' => (string) 'Código : ' . $code,
			'Options' => (string) 'OpenTrack=True,ClickTrack=True'
		);
		
		try{
			$response = $client->SendTransactionalEmail($parameters);
		}
		catch(SoapFault $e){
			echo $client->__getLastRequest();
		}*/
		
		require("phpmailer/class.phpmailer.php");
		require("phpmailer/class.smtp.php");



$mail = new PHPMailer();

// don't use this line of code for godaddy.com
//$mail->IsSMTP();

// set mailer to use godaddy relay server
$mail->Host = "relay-hosting.secureserver.net";
//relay-hosting.secureserver.net

// specify main and backup server - not needed for godaddy relay server
//$mail->SMTPAuth = true; // turn on SMTP authentication

/* commented out as they are not needed
$mail->Username = "jondoe"; // SMTP username
$mail->Password = "ADeer"; // SMTP password
*/

$mail->From = "from@example.com";
$mail->FromName = "Mailer";
$mail->AddAddress($email, "Usuario");
//$mail->AddAddress("ellen@example.com");

// name is optional
//$mail->AddReplyTo("info@example.com", "Information");

$mail->WordWrap = 50;

// set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");

// add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");

// optional name
$mail->IsHTML(true);

// set email format to HTML

$mail->Subject = "Código de confirmación";
$mail->Body = "Código : " . $code;
$mail->AltBody = "Código : " . $code;

if(!$mail->Send())
{
echo "Message could not be sent. <p>";
echo "Mailer Error: " . $mail->ErrorInfo;
exit;
}
		
		
		
	}
}else if(isset($_POST['code'])){
	
	$userCode = $_POST['code'];
	
	if($userCode == $_SESSION["code"]){
		echo "true";
		$_SESSION["confirmated"] = true;
		$_SESSION["authorized"] = true;
	}else{
		echo "false";
		$_SESSION["confirmated"] = false;
	}
	
}

?>