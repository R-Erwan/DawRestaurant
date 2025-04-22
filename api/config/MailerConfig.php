<?php

namespace config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerConfig{

    /**
     * @throws Exception
     */
    public static function getMailer():PHPMailer{
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;
        $mail->Port = 587;
        $mail->setFrom(MAIL_USER, 'Le Resto');

        return $mail;
    }

}
