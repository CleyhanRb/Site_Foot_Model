<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
//required files
require 'INCLUDES/phpmailer/src/Exception.php';
require 'INCLUDES/phpmailer/src/PHPMailer.php';
require 'INCLUDES/phpmailer/src/SMTP.php';
 
$mail = new PHPMailer(true);
 
    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;             //Enable SMTP authentication
    $mail->Username   = 'tournois.unibde@gmail.com';   //SMTP write your email
    $mail->Password   = 'fnjp acet lteb fhnz';      //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port       = 465;                                    
 
    //Recipients
    $mail->setFrom( "tournois.unibde@gmail.com", "UNI BDE"); // Sender Email and name
    $mail->addAddress('cleyhan.robasse@gmail.com');     //Add a recipient email  
    $mail->addReplyTo("tournois.unibde@gmail.com", "UNI BDE"); // reply to sender email
 
    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = "Test PHP";   // email subject headings
    $mail->Body    = "Ceci est un test en php"; //email message
      
    // Success sent message alert
    $mail->send();
    echo
    " 
    <script> 
     alert('Message was sent successfully!');
     document.location.href = 'index.php';
    </script>
    ";