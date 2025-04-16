<?php

namespace services;

use config\MailerConfig;
use PHPMailer\PHPMailer\Exception;

require_once 'config/MailerConfig.php';

class MailService{
    /**
     * @throws Exception
     */
    public static function sendReservationConfirmed(string $toEmail, string $toName, string $date, string $time): bool {
        if(!ACTIVE_MAIL){
            return false;
        }

        $mail = MailerConfig::getMailer();
        try {
            $mail->addAddress($toEmail, $toName);
            $mail->Subject ='Confirmation de votre réservation';
            $mail->Body = "Bonjour $toName \n. 
            Votre réservation pour le $date à $time, à bien été prise en compte.\n 
            Lorsque nous auront validé votre réservation, celle-ci apparaitra confirmé dans votre espace utilisateur\n
            À bientot !";


            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public static function sendResetPasswordLink(string $toEmail, string $token): bool {
        if(!ACTIVE_MAIL){
            return false;
        }

        $mail = MailerConfig::getMailer();
        try {
            $resetLink = DOMAIN_NAME . "/reset-password.php?token=$token";
            $mail->addAddress($toEmail);
            $mail->Subject ='Réinialisation de votre mot de passe';
            $mail->Body = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$resetLink'>$resetLink</a><br>Ce lien expire dans 30 minutes.";
            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
            return false;
        }
    }
}