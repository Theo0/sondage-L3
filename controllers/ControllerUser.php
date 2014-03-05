<?php

require_once "Controller.php";
require_once ROOT . "/models/User.php";
require_once ROOT . "/models/Mail.php";

class ControllerUser extends Controller{
	
	private $user;

	public function __construct() {
		parent::__construct();

		//Création du model utilisateur pour communiquer avec la base de données
		$this->user = new User();
	}

	/* Affichage de la page d'inscription */
	public function afficherInscription() {
		$this->vue = new Vue("Inscription");

		//Si le contrôlleur possède des erreurs de référencées
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);//Envoi des erreurs à la vue

		$this->vue->generer(array());
	}

	/* Affichage de la page quand l'inscription a terminé avec succée */
	public function afficherInscriptionTerminee(){
		$this->vue = new Vue("InscriptionTerminee");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}

	/* Affichage de la page quand l'activation du compte a terminé avec succée */
	public function afficherValidationTerminee(){
		$this->vue = new Vue("ValidationTerminee");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}

	/* Affichage de la page quand l'activation du compte a terminé avec une erreur */
	public function afficherErreurValidation(){
		$this->vue = new Vue("ErreurValidation");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}

	/* Affichage de la page de connexion */
	public function afficherConnexion(){
		$this->vue = new Vue("Connexion");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}

	/* Page affichée après une connexion réussie */
	public function afficherConnexionTerminee(){
		$this->vue = new Vue("ConnexionTerminee");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	
	/* Affichage de la page de mot de passe perdu */
	public function afficherMDPPerdu(){
		$this->vue = new Vue("MDPPerdu");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	
	/* Affichage de la page validant l'envoi de l'email contenant le lien pour réinitialiser le mot de passe */
	public function afficherEnvoiMDPPerdu(){
		$this->vue = new Vue("EnvoiMDPPerdu");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}
	
	/* Affichage de la page de la page de réinitialisation de mot de passe après
	** avoir cliqué sur le lien reçu par email pour un mot de passe perdu
	***/
	public function afficherResetMDPPerdu(){
		$this->vue = new Vue("ResetMDPPerdu");
		
		if(empty($_GET["email"]) || empty($_GET["hash_validation"])){
			$this->addErreur("Le lien que vous avez utilisé pour récupérer votre mot de passe n'est pas valide");
		} else{ 
			$this->user->setEmail($_GET["email"]);
			$this->user->setHashValidation($_GET["hash_validation"]);
			
			if( false === $this->user->is_valid_email_hash())
				$this->addErreur("Le lien que vous avez utilisé pour récupérer votre mot de passe n'est pas valide");
		}
		
		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array("user" => $this->user));
	}
	
	/* Affichage de la page validant la réinitialisation du mot de passe */
	public function afficherValidationMDPPerdu(){
		$this->vue = new Vue("ValidationMDPPerdu");

		if( !empty($this->erreurs) )
			$this->vue->setErreurs($this->erreurs);

		$this->vue->generer(array());
	}

	/* Valide un compte utilisateur en utilisant la valeur de hashage 
	   @param: $_GET["hash"] valeur de hashage permettant d'activer un compte*/
	public function valider_compte(){
		//Validation du compte en base
		$retourValidation = $this->user->valider_compte_avec_hash($_GET["hash"]);
		
		//Validation réussie
		if( $retourValidation ){
			//Affichage de la confirmation de validation du compte
			$this->afficherValidationTerminee();

		//Erreur de validation
		} else {
			//Affichage de l'erreur de validation du compte
			$this->afficherErreurValidation();
		}
	}

	/* Inscription d'un utilisateur avec les informations reçues de $_POST: nom_utilisateur, password, email, avatar */
	public function inscription(){
		//Récupération des champs du formulaire et insertion dans le modèle
		$this->user->POSTToVar($_POST);

		/* Vérification de la validité nom d'utilisateur */ 
		
		$validateNomUtilisateur = $this->user->validateNom();
		if( $validateNomUtilisateur !== 1 ){
			$this->addErreur($validateNomUtilisateur);
		}
	
		/* Vérification de la validité de l'adresse email */
		$validateEmail = $this->user->validateEmail();
		if($validateEmail !==1 ){
			$this->addErreur($validateEmail);
		}

		/* Vérification de la validité du mot de passe */
		$validateMotDePasse = $this->user->validateMotDePasse();
		if($validateMotDePasse !== 1){
			$this->addErreur($validateMotDePasse);
		}

		/* Vérification de la concordance du mot de passe de confirmation */
		if( $this->user->getMdp() != $this->user->getMdpVerif() ){
			$this->addErreur("Le mot de passe de vérification n'est pas identique à votre mot de passe");
		}

		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherInscription(); // On réaffiche le formulaire d'inscription avec les erreurs
			}

		// Ajout de l'utilisateur en base
		} else{
			

			//Début d'une transaction
			$this->user->beginTransaction();

			// Ajout du membre en base et récupération de l'identifiant (ou du message d'erreur)
			$id_utilisateur = $this->user->addUser();

			// Si la base de données a bien voulu ajouter l'utliisateur (pas de doublons)
			if (ctype_digit($id_utilisateur)) {

				// On transforme la chaine en entier
				$id_utilisateur = (int) $id_utilisateur;
	
				// Preparation du mail
				$mail = new Mail('activationInscription', array($this->user->getHashValidation()));
				
				//Envoi du mail
				$retourMail = $mail->send($this->user->getEmail());

				//Si l'email a bien été envoyé
				if( $retourMail === 1 ){
					//Confirmation de la création de l'utilisateur en base
					$this->user->commit(); 
				
					// Affichage de la confirmation de l'inscription
					$this->afficherInscriptionTerminee();					

				//Si l'email n'a pas pu être envoyé				
				} else{
					//Ajout de l'erreur d'envoi du mail
					$this->addErreur($retourMail); 
			
					//Suppression de l'utilisateur en base
					$this->user->callback(); 

					// On reaffiche le formulaire d'inscription avec l'erreur d'envoi du mail
					$this->afficherInscription();
				}
	
			// Gestion des doublons
			} else {

				// Changement de nom de variable (plus lisible)
				$erreur =& $id_utilisateur;
	
				// On vérifie que l'erreur concerne bien un doublon
				if (23000 == $erreur[0]) { // Le code d'erreur 23000 siginife "doublon" dans le standard ANSI SQL
					
					//Récupération de la valeur dupliquée
					preg_match("`Duplicate entry '(.+)' for key '(.+)'`is", $erreur[2], $valeur_probleme);
					$valeur_probleme = $valeur_probleme[1];
		
					//Si le champ dupliqué est l'adresse email
					if ($this->user->getEmail() == $valeur_probleme) {
						$this->addErreur($erreurs_inscription[] = "Cette adresse e-mail est déjà utilisée.");
		
					//Si le champ dupliqué n'a pas pu être identifié
					} else {
						$this->addErreur("Erreur ajout SQL : doublon non identifié présent dans la base de données.");
					}
	
				} else {
					//Ajout du message d'erreur SQL
					$this->addErreur(sprintf("Erreur ajout SQL (SQLSTATE = %d).", $erreur[0]));
				}
	
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On reaffiche le formulaire d'inscription avec l'erreur de doublon
					$this->afficherInscription();
				}
			}			
		}
			
	}


	/* Connexion d'un utilisateur 
	   @params: $_POST["email"] adresse email de l'utilisateur
		    $_POST["mdp"] mot de passe de l'utilisateur
	*/
	public function connexion(){
		//Récupération des champs du formulaire
		$this->user->POSTToVar($_POST);

		/* Vérification de la validité de l'adresse email */
		$validateEmail = $this->user->validateEmail();
		if($validateEmail !==1 ){
			$this->addErreur($validateEmail);
		}

		/* Vérification de la validité du mot de passe */
		$validateMotDePasse = $this->user->validateMotDePasse();
		if($validateMotDePasse !== 1){
			$this->addErreur($validateMotDePasse);
		}		

		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
			if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
				//Redirection vers la page contenant le formulaire avec envoi des erreurs
				header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
			} else{
				$this->afficherConnexion(); // On réaffiche le formulaire de connexion avec les erreurs
			}

		// Connexion de l'utilisateur
		} else{
			// combinaison_connexion_valide() est définit dans ~/modeles/membres.php
			$infos_utilisateur = $this->user->combinaison_connexion_valide();

			// Si les identifiants sont valides
			if (false !== $infos_utilisateur) {
				// On enregistre les informations dans la session
				$_SESSION['id']     = $infos_utilisateur['id'];
				
				// On enregistre "token" : user agent et IP de l'utilisateur concaténé et hashé
				$token = $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'];
				$_SESSION['token']  = sha1($token);
		
				// Affichage de la confirmation de la connexion
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					$this->afficherConnexionTerminee();
				}
			} else {
				$this->addErreur("Couple nom d'utilisateur / mot de passe inexistant.");
		
				if(isset($_POST["redirect"])){//Si le formulaire d'inscription provient d'une autre page que la page d'inscription
					//Redirection vers la page contenant le formulaire avec envoi des erreurs
					header("Location:" . $_POST["redirect"] . "&erreurs=" . serialize($this->erreurs));
				} else{
					// On réaffiche le formulaire de connexion
					$this->afficherConnexion();
				}
			}
		}
	}
	
	/* Envoi d'un email à l'utilisateur possédant l'adresse email $_POST["email"] avec un lien pour enrer un nouveau mot de passe
	   @params: $_POST["email"] adresse email de l'utilisateur
	*/
	public function envoiEmailRecuperationMDP(){
		//Récupération des champs du formulaire
		$this->user->POSTToVar($_POST);

		/* Vérification de la validité de l'adresse email */
		$validateEmail = $this->user->validateEmail();
		if($validateEmail !==1 ){
			$this->addErreur($validateEmail);
		}

		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
				$this->afficherMDPPerdu(); // On réaffiche le formulaire de mot de passe perdu avec les erreurs
		// Envoi de l'email à l'utilisateur
		} else{
			// email_valide() est définit dans ~/modeles/membres.php
			$infos_utilisateur = $this->user->is_valid_email();

			// Si l'email existe en base
			if (false !== $infos_utilisateur) {
				$hash_validation = $infos_utilisateur['hash_validation'];
				
				// Preparation du mail
				$mail = new Mail('recuperationMDPPerdu', array($hash_validation, $this->user->getEmail()));
				
				// On envoi l'email de récupération du mot de passe
				$retourMail = $mail->send($this->user->getEmail());
				
				//Si l'email a bien été envoyé
				if( $retourMail === 1 ){
					$this->afficherEnvoiMDPPerdu();
				//Si l'email n'a pas pu être envoyé				
				} else{
					//Ajout de l'erreur d'envoi du mail
					$this->addErreur($retourMail);
					
					// On réaffiche le formulaire de mot de passe perdu avec les erreurs
					$this->afficherMDPPerdu();
				}	
			} else {
				$this->addErreur("Cet adresse email ne correspond à aucun utilisateur de notre site");

				// On réaffiche le formulaire de mot de passe perdu avec les erreurs
				$this->afficherMDPPerdu();
			}
		}
	}
	
	
	/* Modifie le mot de passe d'un utilisateur après l'envoi du formulaire de mot de passe perdu
	   @params: $_POST["mdp"] nouveau mot de passe de l'utilisateur
		    $_POST["mdp_verif"] confirmation du nouveau mot de passe de l'utilisateur
		    $_POST["hash_validation"] hash de validation de l'utilisateur
		    $_POST["email"] email de l'utilisateur
	*/
	public function modifierMDPPerdu(){
		//Récupération des champs du formulaire et ajout dans le modèle user
		$this->user->POSTToVar($_POST);

		/* Vérification de la validité de l'adresse email */
		$validateEmail = $this->user->validateEmail();
		if($validateEmail !==1 ){
			$this->addErreur($validateEmail);
		}
		
		/* Vérification de la validité du mot de passe */
		$validateMotDePasse = $this->user->validateMotDePasse();
		if($validateMotDePasse !== 1){
			$this->addErreur($validateMotDePasse);
		}

		/* Vérification de la concordance du mot de passe de confirmation */
		if( $this->user->getMdp() != $this->user->getMdpVerif() ){
			$this->addErreur("Le mot de passe de vérification n'est pas identique à votre mot de passe");
		}

		//Si au moins un des champs n'est pas valide
		if( !empty($this->erreurs) ){
				$this->afficherResetMDPPerdu(); // On réaffiche le formulaire de mot de passe perdu avec les erreurs
		//
		} else{
			// Remplissage du modèle user avec l'utilisateur identifié par l'email $_POST["email"] et le hash de validation $_POST["hash_validation"]
			if( false === $this->user->construct_with_email_hash() ){
				$this->addErreur("Impossible de récupérer votre compte sur notre site");
				$this->afficherResetMDPPerdu();
			} else{
				$this->user->setMdp($_POST["mdp"]);
				
				//Mise à jour du mot de passe de l'utilisateur
				if(false === $this->user->updateUser()){
					$this->addErreur("Nous somme dans l'impossibilité de réinitialiser votre mot de passe, merci de réessayer dans un instant");
					$this->afficherResetMDPPerdu();
				//Redirection vers la page validant la modification du mot de passe
				} else{
					$this->afficherValidationMDPPerdu();
				}
			}
		}
	}
	
}
?>






<?php
	if( !empty( $_GET["action"] ) && empty($_GET["controller"])){ // Appel d'une méthode de la classe sans passer par l'index.php
		$controller = new controllerUser();
		if( method_exists( $controller , $_GET["action"] ) ){ // Vérification: la méthode demandée existe dans le contrôleur
			if( !empty( $_GET["params"] ) ){
				$controller->$_GET["action"]( $_GET["params"] ); // Exécution de l'action demandée avec des paramètres
			}
			else{
				$controller->$_GET["action"](); // Exécution de l'action demandée sans paramètres
			}
		}
		else{
			$erreur = "Impossible d'effectuer l'action demandée";
		}
	}
?>
