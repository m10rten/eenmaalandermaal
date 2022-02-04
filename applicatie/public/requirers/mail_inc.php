<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;
$mail->isSMTP();
// $mail->Host = 'iproject12mail.ip.aimsites.nl';
$mail->Host = 'mail.ip.aimsites.nl';
$mail->SMTPAuth = false;
$mail->Port = 21012;

$mail->setFrom('from@example.com', 'Mailer');
