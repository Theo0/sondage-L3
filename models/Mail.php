<?php

require ROOT . '/public/lib/PHPMailer/class.phpmailer.php'; //Librairie d'envoi de mails

class Mail{

	private $templatesFolder;
	private $mail;
	private $actualTemplate;

	public function __construct($template, $params) {
		$this->templatesFolder = ROOT . '/public/templates/mails/'; //Répertoire contenant les mails pouvant être envoyés
		$this->actualTemplate = $this->templatesFolder . $template . '.php'; //Fichier contenant les informations du mail à envoyer

		require $this->actualTemplate;

		//Création de l'objet permettant d'envoyer des emails
		$this->mail = new PHPMailer();
		
		$this->mail -> IsSMTP();
		$this->mail->IsHTML(true);
		// telling the class to use SMTP
		$this->mail -> SMTPAuth = true;
		$this->mail->SMTPSecure = 'ssl';
		// turn on SMTP authentication
		$this->mail -> Host = EMAIL_HOST;
		//$this->mail->SMTPDebug  = 2;  
		// SMTP server
		$this->mail -> Port = EMAIL_PORT;
		$this->mail -> Username = EMAIL_USERNAME;
		$this->mail -> Password = EMAIL_PASSWD;
		$this->mail -> FromName = $fromName;
		$this->mail -> From = EMAIL_FROM;
		$this->mail->CharSet = EMAIL_CHARSET;	
		$this->mail -> Subject = $sujetMail;
		$this->mail -> Body = $bodyMail;
	}

	public function send($receiver){
		$this->mail -> AddAddress($receiver);

		// Si une erreur survient lors de l'envoi du mail
		if (!$this->mail -> Send()) {
			return "Une erreur est survenue lors de l'envoi de l'email";

		//Email envoyé avec succés
		} else {
			return 1;
		}
	}
}

?>
