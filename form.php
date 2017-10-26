<?php
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/Twilio/autoload.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
set_time_limit(0);
$configContents  = file_get_contents('http://futuredigital.co.uk/form/config.json');
$configJson = json_decode($configContents);
$sid = $configJson->ssid;
$token = $configJson->token;
$key = $configJson->pinkey;
$numbers = $configJson->numbers;
$emails = $configJson->email;
$client = new Client($sid, $token);
if(isset($_POST["submit"])){
	$text = $_POST["message"];
	$pin = $_POST["pin"];
	if($pin==$key){
		for($x = 0; $x < count($numbers); $x++){
			$client->messages->create(
				$numbers[$x],
				array(
					'from' => '+442033898015',
					'body' => $text
					)
				);
		}
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;
		//$mail->Debugoutput = 'html';
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Username = $configJson->username;
		$mail->Password = $configJson->password;
		$mail->Port = 465; 
		$mail->From = $configJson->username;
		$mail->FromName = "Alert warning system";
		for($y = 0; $y < count($emails); $y++){
			$mail->addAddress($emails[$y]);
		}
		$mail->WordWrap = 50;
		$mail->Subject = "Alert to All staff";
		$mail->Body = $text;
		if(!$mail->send()){
			echo 'Mailer error: ' . $mail->ErrorInfo;
		}		
		header("Location:http://futuredigital.co.uk/form/sent.php");
		exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Alert Broadcast</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Alert Broadcast</a></h1>
			<form id="form_54803" class="alertform"  method="post" action="form.php">
				<div class="form_description">
					<h2> Alert Broadcast</h2>
						<p> Send alert to all staff</p>
				</div>						
				<ul >
					<li id="li_1" >
						<label class="description" for="message">Message </label>
							<div>
								<textarea id="message" name="message" class="element textarea small" maxlength="320" value="<?php echo $text; ?>"></textarea> 
							</div> 
					</li>
					<li id="li_2" >
						<label class="description" for="pin">Pin </label>
							<div>
								<input id="pin" name="pin" class="element text small" type="text" maxlength="4" value=""/> 
							</div> 
					</li>
					<li class="buttons">
						<input type="hidden" name="form_id" value="54803" />
							<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
					</li>
				</ul>
		</form>
	</div>
	<img id="bottom" src="bottom.png" alt="">
</body>
</html>