<?php

namespace App\CustomActions;

use Agrandesr\actions\ActionBuilder;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerAction extends ActionBuilder {
    protected string $envFlag;
    protected mixed $addAddress=false;
    protected mixed $addCC=false;
    protected mixed $addBCC=false;
    protected mixed $addAttachment=false;
    protected string $subject;
    protected string $body;
    protected string $altBody;

    public function execute() {
        $flag=(empty($this->envFlag))?'MAIL_':$this->envFlag.'_MAIL_';
        $Host=$_ENV[$flag . 'HOST'];
        $Port=$_ENV[$flag . 'PORT'];
        $Username=$_ENV[$flag . 'USERNAME'];
        $Password=$_ENV[$flag . 'PASSWORD'];
        $Security=$_ENV[$flag . 'SECURITY'] ?? true;
        $humanName=$_ENV[$flag . 'SENDNAME'] ?? $Username;

        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();
        $mail->Host           = $Host;
        $mail->SMTPAuth       = $Security;
        $mail->Username       = $Username;
        $mail->Password       = $Password;
        $mail->SMTPSecure     = 'tls';
        $mail->Port           = $Port;

        //Recipients
        $mail->setFrom($Username, $humanName);
        //AddAddress
        if(is_array($this->addAddress)) {
            foreach ($this->addAddress as $address) {
                if(is_array($address)) $mail->addAddress($address[0],$address[1]);
                else $mail->addAddress($address);
            }
        } elseif(is_string($this->addAddress)) $mail->addAddress($this->addAddress);
        //AddCC
        if(is_array($this->addCC)) {
            foreach ($this->addCC as $addCC) {
                if(is_array($addCC)) $mail->addCC($addCC[0],$addCC[1]);
                else $mail->addCC($addCC); 
            }
        } elseif(is_string($this->addCC)) $mail->addCC($this->addCC);
        //AddBCC
        if(is_array($this->addBCC)) {
            foreach ($this->addBCC as $addBCC) {
                if(is_array($addBCC)) $mail->addBCC($addBCC[0],$addBCC[1]);
                else $mail->addBCC($addBCC); 
            }
        } elseif(is_string($this->addBCC)) $mail->addBCC($this->addBCC);

        //Attachments
        if(is_array($this->addAttachment)) {
            foreach ($this->addAttachment as $addAttachment) {
                if(is_array($addAttachment)) $mail->addAttachment($addAttachment[0],$addAttachment[1]);
                else $mail->addAttachment($addAttachment); 
            }
        } elseif(is_string($this->addBCC)) $mail->addAddress($this->addBCC);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $this->subject??'No subject';
        if(file_exists($this->body)) $mail->Body=readfile($this->body);
        else $mail->Body    = $this->body ?? 'No body';
        $mail->AltBody = $this->altBody ?? $this->body ?? 'No Body';

        $mail->send();
    }
}