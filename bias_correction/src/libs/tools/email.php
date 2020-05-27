<?php

require '/var/www/html/libs/PHPMailer/class.phpmailer.php';

function send_msg($subject, $content, $to){
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->IsHTML(TRUE);
    $mail->CharSet = "UTF-8";
    //Set your existing gmail address as user name    
    $mail->SMTPSecure = 'tsl';
    $mail->Host = 'ssl://smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->FromName = "CCAFS-Climate";
    $mail->SMTPDebug = 1;

    $mail->AddAddress($to);
	$mail->Subject = $subject;
	$mail->Body = $content;    
    
    //Set your existing gmail address as user name
    $mail->Username = "";
    $mail->From = "";
    //Set the password of your gmail address here
    $mail->Password = "";

    if(!$mail->Send()) {
        echo 'Email is not sent.';
        echo 'Email error: ' . $mail->ErrorInfo;
    } 
    else {
        echo 'Email has been sent.';
    }
}
?>