<?php
namespace App\Helpers;

use PHPMailer;



class Helpers{
    public function Exceptions($id){


    }

    public function bodyConfirmEmail($first_name){
        return "Beste ". $first_name .", \n\n
        Hartelijk dank voor uw deelname aan deze prijsvraag.\n
        Nu is het nog even afwachten, want de speelperiode is nog niet over.\n
        De winnaars van deze prijs krijgen persoonlijk bericht.\n
        \n
        Bedankt,\n
        Het CocaCola-team";
    }
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
        $mail->Body    = $this->bodyConfirmEmail($first_name);
        $mail->AltBody = $this->bodyConfirmEmail($first_name);

        if(!$mail->send()) {

            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'success';
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
        //$mail->addReplyTo('info@example.com', 'Information');
        $mail->isHTML(false);                                  // Set email format to HTML

        $mail->Subject = 'Wij hebben een winnaar geselecteerd voor deze periode';
        $mail->Body    = 'de winnaar is' . $winner;
        $mail->AltBody = 'de winnaar is' . $winner;

        if(!$mail->send()) {

            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'success';
        }
    }
}