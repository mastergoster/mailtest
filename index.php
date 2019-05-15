<?php
date_default_timezone_set('Europe/Paris') ;
session_start();
require_once 'vendor/autoload.php';

/**
* envoie un email
* @return string
*/
function envoiMail($objet, $mailto, $msg, $cci = true)//:string
{
	require 'config.php';
	if(!is_array($mailto)){$mailto = [ $mailto ];}
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))->setUsername($defaultmail)->setPassword($mailpwd);
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($objet))->setFrom([$defaultmail]);
	if ($cci){$message->setBcc($mailto);
	}else{$message->setto($mailto);}
	if(is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg))
	{$message->setBody($msg["html"], 'text/html');
    $message->addPart($msg["text"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("html", $msg) ){
    $message->setBody($msg["html"], 'text/html');
    $message->addPart($msg["html"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("text", $msg)){
    $message->setBody($msg["text"], 'text/plain');
	}else if(is_array($msg)){
    die('erreur une clé n\'est pas bonne'); 
	}else{$message->setBody($msg, 'text/plain');}
	return $mailer->send($message);
}

function idcool($length = 12){
    $text="azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";
    return substr(md5(str_shuffle(str_repeat($text, $length))), 0, $length);
}

if(isset($_SESSION['mail']) && $_SESSION['mail'] == 'ok'){
    $token = idcool();
    fopen($token, 'w');
    envoiMail('visite sur page', 'contact@apprendre.co', 'il y a eu une visite sur la page le token est : '.$token);
    echo 'votre surprise est envoyé ;)';
    unset($_SESSION['mail']);
}else{
    $_SESSION['mail'] = 'ok';
    echo 'Merci de rafraichir la page pour avoir votre surprise !!!';
}
 

//$text="azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";
//var_dump(substr(md5(str_shuffle(str_repeat($text, 12))), 0, 12));
//var_dump(substr(uniqid(), 1,13));
