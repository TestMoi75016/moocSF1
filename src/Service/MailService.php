<?php

namespace App\Service;

class MailService
{
public function sendMail(string $email, string $subject, string $message)
{
     //code pour envoyer un mail:
    //$mail->setSubject($sujet)
    //$mail->settMessage($message);
    //$mail->mail->send();

    dd($subject);
}


}