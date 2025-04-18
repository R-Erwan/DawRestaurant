<?php

namespace services;

use config\MailerConfig;
use PHPMailer\PHPMailer\Exception;


class MailService
{
    /**
     * @throws Exception
     */
    public static function sendReservationConfirmed(string $toEmail, string $toName, string $date, string $time): void
    {
        if (!ACTIVE_MAIL) {
            return;
        }

        $mail = MailerConfig::getMailer();
        try {
            $mail->addAddress($toEmail, $toName);
            $mail->Subject = 'Confirmation de votre réservation';
            $mail->Body = "Bonjour $toName \n. 
            Votre réservation pour le $date à $time, à bien été prise en compte.\n 
            Lorsque nous auront validé votre réservation, celle-ci apparaitra confirmé dans votre espace utilisateur\n
            À bientôt !";


            $mail->send();
        } catch (Exception) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
            return;
        }
    }

    /**
     * @throws Exception
     */
    public static function sendResetPasswordLink(string $toEmail, string $token): void
    {
        if (!ACTIVE_MAIL) {
            return;
        }

        $mail = MailerConfig::getMailer();
        try {
            $resetLink = DOMAIN_NAME . "/reset-password.php?token=$token";
            $mail->addAddress($toEmail);
            $mail->Subject = 'Récupération de votre mot de passe';
            $mail->Body = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$resetLink'>$resetLink</a><br>Ce lien expire dans 30 minutes.";
            $mail->send();
        } catch (Exception) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
            return;
        }
    }
}