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
        $mail->Host = 'smtp.gmail.com';
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64'; // optionnel mais conseillé
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'contact.leresto@gmail.com';
        $mail->Password = "tjvxgwcrrkydemar";
        $mail->Port = 587;
        $mail->setFrom('contact.leresto@gmail.com', 'Le Resto');

        return $mail;
    }

}
