<?php
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;								
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/Twilio/autoload.php';						//Include Twilio API for sms system
require __DIR__ . '/PHPMailer/src/Exception.php';				//Include PHPMailer for mail system
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
set_time_limit(0);												//Dont timeout if SMTP takes longer than 45 seconds to send
$configContents  = file_get_contents('***');					//Json config file containing api keys and such
$configJson = json_decode($configContents);						//Decode Json file into readable string
$sid = $configJson->ssid;										//Twilio sid
$token = $configJson->token;									//Twilio token api key
$key = $configJson->pinkey;										//Pin to authorise sending message
$numbers = $configJson->numbers;								//Array mobile numbers
$emails = $configJson->email;									//Array of email addresses
$client = new Client($sid, $token);								//Twilio sms client, bind sid and token
if(isset($_POST["submit"])){									//If submit button clicked
	$text = $_POST["message"];									//Store text from message box
	$pin = $_POST["pin"];										//Store pin entered by user
	if($pin==$key){												//If pin correct
		for($x = 0; $x < count($numbers); $x++){				//Send sms for each number in numbers array
			$client->messages->create(
				$numbers[$x],
				array(
					'from' => '+442033898015',
					'body' => $text
					)
				);
		}
		$mail = new PHPMailer(true);							//Init PHPMailer
		$mail->isSMTP();										//Enable SMTP
		//$mail->SMTPDebug = 2;									//Verbose logging
		//$mail->Debugoutput = 'html';							//Html log format
		$mail->SMTPSecure = 'ssl';								//SSL protocol, tsl is also available
		$mail->Host = "smtp.gmail.com";							//Gmail host smtp server
		$mail->SMTPAuth = true;									//Enable smtp authentication
		$mail->Username = $configJson->username;				//Get username from json
		$mail->Password = $configJson->password;				//Get password from json
		$mail->Port = 465; 										//ssl port
		$mail->From = $configJson->username;					//Use username for sender email
		$mail->FromName = "Alert warning system";				//Sender name Default: Root User
		for($y = 0; $y < count($emails); $y++){					//Add email addresses from emails array
			$mail->addAddress($emails[$y]);
		}
		$mail->WordWrap = 50;									//Wraps after 50 chars
		$mail->Subject = "Alert to All staff";					//Subject line
		$mail->Body = $text;									//Use message text for the body of email
		if(!$mail->send()){										//Send mail, thrwo error on failure
			echo 'Mailer error: ' . $mail->ErrorInfo;
		}		
		header("Location:***");									//Redirect after sending
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