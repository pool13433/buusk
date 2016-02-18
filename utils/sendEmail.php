<?php

// ############# config mail ################
$mail = new PHPMailer;
$mail->CharSet = "UTF-8";
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();
// Set mailer to use SMTP
//$mail->Host = 'smtp.googlemail.com:465';
$mail->Host = Config::$MAIL_HOST;
$mail->Port = Config::$MAIL_PORT;
$mail->SMTPSecure = Config::$MAIL_SMTPSECURE;
$mail->SMTPAuth = true;
$mail->Username = Config::$MAIL_USERNAME;
$mail->Password = Config::$MAIL_PASSWORD;                             // TCP port to connect to

$mail->From = Config::$MAIL_ADMIN_EMAIL;
$mail->FromName = Config::$MAIL_ADMIN_NAME;
//$mail->SetFrom($fromEmail, $fromName);
$mail->Subject = $messageTitle;
$mail->Body = $messageBody;
$mail->AltBody = $messageBody;
$mail->WordWrap = 50;
$mail->MsgHTML($messageBody);
$mail->IsHTML(true);

// ############# config mail ################                           
$mail->AddAddress($creator_news->email, $creator_news->fname . '   ' . $creator_news->lname);
//$mail->AddAddress('thaismilesoft.com@gmail.com', 'ThaiSmilesoft.com');                
############ send email #################              
$is_mail = $mail->Send();
