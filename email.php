<?php
require("class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtp.gmail.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = EMAIL;  // SMTP username
$mail->Password = PASS; // SMTP password
$mail->SMTPSecure = 'tls';
$mail->port = 587;
$mail->From = EMAIL;
$mail->FromName = "XYZ company";
$mail->AddAddress("$email", "Kumar");
$mail->AddAddress("$email");                  // name is optional
$mail->AddReplyTo("$email", "Information");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
$var = array('email' => "$email", 'random' => "$random");
$query_string = http_build_query($var);
$mail->Subject = "Verification link for your account";
$mail->Body    = "Click on the below link to verify your account <br>"."http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verify.php?". $query_string;
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   echo "Mail id doesn't exist<br><br>";
   echo "<a href = 'index.php'> Go to Home </a>";
   exit;
}
echo "Thanks for submitting the form<br>";
echo "A verfication code has been sent to your email-id.  Click on the link to get your account verified  "."<a href='index.php'>Login here </a>";
?>