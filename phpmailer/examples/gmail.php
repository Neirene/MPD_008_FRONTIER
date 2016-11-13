<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

$domain_name = "localhost";
$account_name ="hebunsaisei";
$account_email = "myriabelmonte@gmail.com";

require '../PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "admin@l2frontier.com";

//Password to use for SMTP authentication
$mail->Password = "TaiyouS@isei";

//Set who the message is to be sent from
$mail->setFrom('noreply@l2frontier.com', 'Lineage II Frontier');

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
$mail->addAddress($account_email, $account_name);

//Set the subject line
$mail->Subject = 'Activate your Account';



$embedded_message = '
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Lineage II Frontier Account Activation</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h1>Welcome to Lineage II Frontier</h1>
  <h2>In order to begin playing in our server we need you confirm your game account first by clicking on the link below</h2>
  
    <h1><a href="http://'.$domain_name.'/verify.php/">Activate My Account!</a></h1>

  <p>Remember: Your account is only yours, and we held no responsability if you lose it. We suggest you set a unique password as well.</p>
</div>
</body>
</html>
        
        ';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($embedded_message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
