<?php
namespace App\Helpers;

use PHPMailer;
class Helpers{

    public function getIP(){
        $ip = "none";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip =  $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip =  $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip =  $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function sendConfirmMail($emailTo, $first_name){

        require(base_path().'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

        //  $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.live.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cocagiveaway@hotmail.com';                 // SMTP username
        $mail->Password = 'cocacola12';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom('cocagiveaway@hotmail.com', 'CocaCola');
        $mail->addAddress($emailTo, $first_name);     // Add a recipient
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(false);                                  // Set email format to HTML

        $mail->Subject = 'Bedankt voor het meedoen met de CocaCola prijsvraag!';
        $mail->Body    = 'wanneer je verkozen wordt tot winnaar zul je automatisch een mail krijgen';
        $mail->AltBody = 'wanneer je verkozen wordt tot winnaar zul je automatisch een mail krijgen';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Message has been sent';
        }
    }

    public function sendMailToAdmin($emailTo, $first_name, $winner){

        require(base_path().'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

        // $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.live.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cocagiveaway@hotmail.com';                 // SMTP username
        $mail->Password = 'cocacola12';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom('cocagiveaway@hotmail.com', 'CocaCola');
        $mail->addAddress($emailTo, $first_name);     // Add a recipient
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(false);                                  // Set email format to HTML

        $mail->Subject = 'Wij hebben een winnaar geselecteerd voor deze periode';
        $mail->Body    = 'de winnaar is' . $winner;
        $mail->AltBody = 'de winnaar is' . $winner;

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Message has been sent';
        }
    }
}